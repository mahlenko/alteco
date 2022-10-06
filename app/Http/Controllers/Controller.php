<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function ok(string $message = null, $data = null): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    protected function fail(string $message = null, $errors = null): JsonResponse
    {
        return response()->json([
            'ok' => false,
            'message' => $message,
            'errors' => []
        ]);
    }
}
