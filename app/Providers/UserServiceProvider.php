<?php
namespace App\Providers;

use App\Services\User\UserManager;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('user', UserManager::class);
    }
}