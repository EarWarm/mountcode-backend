<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        auth('api')->logout(true, true);
        return response()->json([
            'status' => true,
            'message' => 'Вы были деавторизованы.'
        ]);
    }
}
