<?php

namespace App\Managers\PaymentSystem\Impl\FreeKassa;

use App\Managers\PaymentSystem\Commons\ActivePayment;
use App\Managers\PaymentSystem\IPaymentSystem;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FreeKassa implements IPaymentSystem
{

    use ValidateTrait;

    static function makePayment(User $user, int $amount): ActivePayment
    {
        $payment = Payment::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_system' => 'freekassa',
        ]);

        $query = [];

        $query['m'] = config('payment.freekassa.shop_id');
        $query['oa'] = $amount;
        $query['o'] = $payment->id;
        $query['currency'] = config('payment.freekassa.currency');
        $query['s'] = self::getSignature($amount, $payment->id);
        $query['lang'] = config('payment.freekassa.lang');
        $query['em'] = $user->email;

        return new ActivePayment(config('payment.freekassa.pay_url') . '?' . http_build_query($query), true);
    }

    protected static function getSignature($amount, $order_id): string
    {
        $hashStr =
            config('payment.freekassa.shop_id')
            . ':' .
            $amount
            . ':' .
            config('payment.freekassa.secret_word_1')
            . ':' .
            config('payment.freekassa.currency')
            . ':' .
            $order_id;

        return md5($hashStr);
    }

    static function result(Request $request): Response
    {
        if (!self::validateSignature($request)) {
            return response('WRONG VALIDATE');
        }

        $data = $request->all();

        $payment = Payment::firstWhere('id', $data['MERCHANT_ORDER_ID']);

        if (!$payment) {
            return response('WRONG PAYMENT');
        }

        if ($payment->amount != $data['AMOUNT']) {
            return response('WRONG AMOUNT');
        }

        if ($payment->status) {
            return self::responseYES();
        }

        $user = $payment->user;

        $coins = $payment->amount;
        if ($payment->amount > 500) {
            $coins += ($payment->amount / 100) * 15;
        }

        $user->coins += $coins;
        $payment->status = true;
        $payment->closed_at = Carbon::now();
        $user->save();
        $payment->save();

        return self::responseYES();
    }

    protected static function responseYES(): Response
    {
        return response('YES');
    }

    protected static function getResultSignature($amount, $order_id): string
    {
        $hashStr =
            config('payment.freekassa.shop_id')
            . ':' .
            $amount
            . ':' .
            config('payment.freekassa.secret_word_2')
            . ':' .
            $order_id;

        return md5($hashStr);
    }
}
