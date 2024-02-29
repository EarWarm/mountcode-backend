<?php

namespace App\Managers\PaymentSystem\Commons;

class ActivePayment
{
    private ?string $paymentUrl;
    private bool $success;

    public function __construct(?string $paymentUrl, bool $success)
    {
        $this->paymentUrl = $paymentUrl;
        $this->success = $success;
    }

    /**
     * @return string
     */
    public function getPaymentUrl(): string
    {
        return $this->paymentUrl;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }


}
