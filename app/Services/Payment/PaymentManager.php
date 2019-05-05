<?php
namespace App\Services\Payment;

use App\Daos\BillDao;
use App\Daos\PaymentDao;

class PaymentManager
{
    protected $paymentDao;

    protected $billDao;

    public function __construct(PaymentDao $paymentDao, BillDao $BillDao)
    {
        $this->paymentDao = $paymentDao;

        $this->billDao = $BillDao;
    }

    public function getBillInfo()
    {
        return $this->billDao->getBillInfo();
    }
}