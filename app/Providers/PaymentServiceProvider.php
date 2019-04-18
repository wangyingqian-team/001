<?php
namespace App\Providers;

use App\Services\Payment\PaymentManager;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('payment', PaymentManager::class);
    }
}