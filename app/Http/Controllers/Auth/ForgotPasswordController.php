<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return response()->json([
                'status' => false,
                'message' => 'Ошибка при отправке электронного письма.'
            ]);
        }

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => true,
                'message' => 'Инструкция для восстановления отправлена на почту'
            ]);
        } else if ($status == Password::INVALID_USER) {
            return response()->json([
                'status' => false,
                'message' => 'Пользователь с указанной почтой не найден'
            ]);
        } else if ($status == Password::RESET_THROTTLED) {
            return response()->json([
                'status' => false,
                'message' => 'Слишком частые запросы инструкции! Попробуй позже.'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => __($status)
        ]);
    }
}
