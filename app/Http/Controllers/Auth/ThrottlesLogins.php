<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ThrottlesLogins
{
    /**
     * Determine if the user has too many failed login attempts.
     *
     * @param Request $request
     * @return bool
     */
    protected function hasTooManyLoginAttempts(Request $request): bool
    {
        return $this->limiter()->tooManyAttempts(
            $this->throttleKey($request), 5
        );
    }

    /**
     * Get the rate limiter instance.
     *
     * @return RateLimiter
     */
    protected function limiter(): RateLimiter
    {
        return app(RateLimiter::class);
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param Request $request
     * @return string
     */
    protected function throttleKey(Request $request): string
    {
        return $request->ip();
    }

    /**
     * Increment the login attempts for the user.
     *
     * @param Request $request
     * @return void
     */
    protected function incrementLoginAttempts(Request $request): void
    {
        $this->limiter()->hit($this->throttleKey($request));
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @return JsonResponse
     */
    protected function sendLockoutResponse(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Слишком много попыток авторизации, попробуйте позже!'
        ]);
    }

    /**
     * Clear the login locks for the given user credentials.
     *
     * @param Request $request
     * @return void
     */
    protected function clearLoginAttempts(Request $request): void
    {
        $this->limiter()->clear($this->throttleKey($request));
    }

    /**
     * Fire an event when a lockout occurs.
     *
     * @param Request $request
     * @return void
     */
    protected function fireLockoutEvent(Request $request): void
    {
        event(new Lockout($request));
    }
}
