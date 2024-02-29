<?php

namespace App\Managers\PaymentSystem;

use App\Managers\PaymentSystem\Commons\ActivePayment;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

interface IPaymentSystem
{
    static function makePayment(User $user, int $amount): ActivePayment;

    static function result(FormRequest $request): Response;
}
