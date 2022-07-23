<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use DateTimeImmutable;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return RedirectResponse|void
     * @throws Exception
     */
    public function authenticated(Request $request, User $user)
    {
        if (!$user->checkExpiredAt(new DateTimeImmutable('now'))) {
            $this->logout($request);

            flash('Ваш аккаунт деактивирован. Продлите подписку для включения доступа.')
                ->warning();

            return redirect()->route('login');
        } else {
            Log::debug(sprintf(
                'Пользователь "%s": аккаунт активен. Доступ открыт.',
                $user->{$this->username()}
            ));
        }
    }

    /**
     * @param Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        flash(trans('auth.failed'))->error();
        return redirect()->route('login');
    }
}
