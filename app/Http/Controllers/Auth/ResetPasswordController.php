<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => $password
                ]);

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response()->json([
                'status' => true,
                'message' => 'Пароль успешно изменён'
            ]);
        } else if ($status == Password::INVALID_TOKEN) {
            return response()->json([
                'status' => false,
                'message' => 'Токен сброса пароля невалиден'
            ]);
        } else if ($status == Password::INVALID_USER) {
            return response()->json([
                'status' => false,
                'message' => 'Пользователь с указанной почтой не найден'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Ошибка при сбросе пароля!'
        ]);
    }
}
