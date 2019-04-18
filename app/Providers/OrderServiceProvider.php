<?php
namespace App\Providers;

use App\Services\Order\OrderManager;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('order', OrderManager::class);
    }
}