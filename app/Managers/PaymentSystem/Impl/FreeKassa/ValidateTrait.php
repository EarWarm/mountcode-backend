<?php

namespace App\Managers\PaymentSystem\Impl\FreeKassa;

use Illuminate\Http\Request;

trait ValidateTrait
{
    protected static function validateSignature(Request $request): bool
    {
        $sign = FreeKassa::getResultSignature($request->input('AMOUNT'), $request->input('MERCHANT_ORDER_ID'));

        if ($request->input('SIGN') != $sign) {
            return false;
        }

        return true;
    }
}
