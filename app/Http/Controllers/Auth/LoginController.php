<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use ThrottlesLogins;

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $data = $request->all();

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse();
        }

        if ($token = auth('api')->attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json([
                'status' => true,
                'token' => $token,
                'message' => 'Вы успешно авторизовались',
                'user' => new UserResource(auth('api')->user())
            ]);
        }

        $this->incrementLoginAttempts($request);

        return response()->json([
            'status' => false,
            'message' => 'Неверная почта или пароль'
        ]);
    }
}
