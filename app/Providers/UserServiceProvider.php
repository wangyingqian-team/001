<?php
namespace App\Providers;

use App\Contracts\User\UserAccountInterface;
use App\Services\User\UserAccountManager;
use Illuminate\Support\ServiceProvider;

/**
 * 用户相关服务提供者
 *
 * @author zhangzhengkun
 */
class UserServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register()
    {
        // 用户账号功能
        $this->app->singleton(UserAccountInterface::class, UserAccountManager::class);
    }
}