<?php
namespace App\Providers;

use App\Services\Item\ItemManager;
use Illuminate\Support\ServiceProvider;

class ItemServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('item', ItemManager::class);
    }
}