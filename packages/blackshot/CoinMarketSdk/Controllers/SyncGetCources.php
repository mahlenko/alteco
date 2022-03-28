<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Models\User;
use Blackshot\CoinMarketSdk\Repositories\UserRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SyncGetCources extends \App\Http\Controllers\Controller
{

    public function index()
    {
//        $export = $this->createExport([
//            'status' => 'payed',
//            'payed_at' => [
//                'from' => '2021-12-01',
//                'to' => '2021-12-24'
//            ],
//        ]);
//        if ($export['success']) {
//            $export_id = $export['info']['export_id'];
//            dump($export_id);
//            $response = $this->getExportDeals($export_id);
//            $response = $this->getExportDeals(6224670);
//            dd($response);
//        }

//        $response = $this->createExport(['status' => 'payed']);
//        $response = $this->getExport(6225384);
//        return $response;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function webhook(Request $request): array
    {
        $validator = Validator::make($request->toArray(), [
            'user_email' => ['required', 'email'],
            'expired_at' => ['required', 'date']
        ]);

        if ($validator->fails()) {
            return [
                'ok' => false,
                'errors' => $validator->errors()
            ];
        }

        $data = $validator->validate();

        /* @var User $user */
        $user = UserRepository::findByEmail($data['user_email']);

        if ($user) {
            $user->setExpiredAt(new DateTimeImmutable($data['expired_at']));
            $user->save();
        } else {
            $user = UserRepository::create(
                'User ' . (User::all()->count() + 1),
                $data['user_email'],
                Str::random(8),
                User::ROLE_USER,
                new DateTimeImmutable($data['expired_at'])
            );
        }

        return [
            'ok' => true,
            'data' => $user
        ];
    }


    /**
     * @param array $params
     * @return array|mixed
     */
//    private function createExport(array $params = [])
//    {
//        $url = config('getcources.server') . '/account/deals/asd';
//        $response = Http::asForm()->post($url, [
//            'key' => config('getcources.token'),
//            'params' => base64_encode(json_encode($params))
//        ]);
//
//        return $response->json();
//    }
//
//    private function getExport(int $export_id)
//    {
//        $url = config('getcources.server') . '/account/exports/' . $export_id;
//        $response = Http::asForm()->post($url, [
//            'key' => config('getcources.token'),
//        ]);
//
//        return $response->json();
//    }
}
