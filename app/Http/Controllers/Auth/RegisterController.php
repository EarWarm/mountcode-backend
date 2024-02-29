<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $data = $request->all();

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if ($user) {
            event(new Registered($user));

            $user->assignRole('user');
            $token = auth('api')->login($user);
            return response()->json([
                'status' => true,
                'token' => $token,
                'message' => 'Аккаунт успешно зарегистрирован.',
                'user' => new UserResource(auth('api')->user())
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Ошибка при регистрации аккаунта!'
        ]);
    }
}
