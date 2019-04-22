<?php
namespace App\Providers;

use App\Contracts\Privilege\PrivilegeInterface;
use App\Services\Privilege\PrivilegeManager;
use Illuminate\Support\ServiceProvider;

/**
 * 权限相关服务提供者
 *
 * @author zhangzhengkun
 */
class PrivilegeServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     */
    public function register()
    {
        // 角色与权限功能
        $this->app->singleton(PrivilegeInterface::class, PrivilegeManager::class);
    }
}