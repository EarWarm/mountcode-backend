<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ChangePasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $request->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Текущий пароль неверен!'
            ]);
        }

        $user->password = $data['password'];
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Пароль успешно изменён!'
        ]);
    }
}
