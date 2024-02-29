<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PaymentRequest;
use App\Managers\PaymentSystem\Impl\FreeKassa\FreeKassa;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __invoke(PaymentRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['payment_system'] != 'freekassa') {
            return response()->json([
                'status' => false,
                'message' => 'Система оплаты выбрана неверно, повторите попытку.'
            ]);
        }

        $user = Auth::user();

        $payment = FreeKassa::makePayment($user, $data['coins']);

        if ($payment->isSuccess()) {
            return response()->json([
                'status' => true,
                'message' => 'Ссылка для оплаты сформирована, страница оплаты будет открыта автоматически.',
                'payment_url' => $payment->getPaymentUrl()
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Ссылка для оплаты не была сформирована, попробуйте позже.'
            ]);
        }
    }

    public function result(string $paymentSystem, Request $request): Response|Application|ResponseFactory
    {
        if (empty($paymentSystem)) {
            return response('WRONG PAYMENT SYSTEM!');
        }

        if ($paymentSystem !== 'freekassa') {
            return response('WRONG PAYMENT SYSTEM!');
        }

        return FreeKassa::result($request);
    }
}
