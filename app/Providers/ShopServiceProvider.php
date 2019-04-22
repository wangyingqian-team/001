<?php
namespace App\Providers;

use App\Contracts\Shop\ShopAccountInterface;
use App\Contracts\Shop\ShopInterface;
use App\Contracts\Shop\ShopOperationLogInterface;
use App\Services\Shop\ShopAccountManager;
use App\Services\Shop\ShopManager;
use App\Services\Shop\ShopOperationLogManager;
use Illuminate\Support\ServiceProvider;

/**
 * 商家相关服务提供者
 *
 * @author zhangzhengkun
 */
class ShopServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register()
    {
        // 商家账号功能
        $this->app->singleton(ShopAccountInterface::class, ShopAccountManager::class);

        // 商家店铺功能
        $this->app->singleton(ShopInterface::class, ShopManager::class);

        // 商家操作日志功能
        $this->app->singleton(ShopOperationLogInterface::class, ShopOperationLogManager::class);
    }
}