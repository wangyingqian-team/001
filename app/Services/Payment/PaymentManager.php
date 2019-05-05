<?php
namespace App\Services\Payment;

use App\Daos\PaymentDao;

class PaymentManager
{
    protected $paymentDao;

    public function __construct(PaymentDao $paymentDao)
    {
        $this->paymentDao = $paymentDao;
    }


}