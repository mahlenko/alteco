<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\UserRegistered;
use App\Providers\RouteServiceProvider;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Models\User;
use DateTimeImmutable;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'agreement' => ['accepted'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \Blackshot\CoinMarketSdk\Models\User
     * @throws \Exception
     */
    protected function create(array $data)
    {
        $tariff_default = TariffModel::where('default', true)->first();
        if (!$tariff_default) {
            throw ValidationException::withMessages([
                'tariff_id' => 'Registration is temporarily not possible.'
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'tariff_id' => $tariff_default->id
        ]);

        $tariff_expired = new DateTimeImmutable('+' . $tariff_default->days .' days');
        $user->setExpiredAt($tariff_expired);
        $user->save();

        $user->notify(new UserRegistered($data['password'], $tariff_expired));

        return $user;
    }
}
