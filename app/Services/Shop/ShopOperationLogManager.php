<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopOperationLogInterface;

/**
 * 商家操作日志相关功能
 *
 * @author zhangzhengkun
 */
class ShopOperationLogManager implements ShopOperationLogInterface
{
    /**
     * 添加商家操作日志
     *
     * @param int $shopId 店铺id
     * @param string $sellerAccount 操作账号
     * @param string $content 操作内容
     * @param string $route 操作路由
     * @param string $ip IP
     *
     * @return int
     */
    public function addOperationLog($shopId, $sellerAccount, $content, $route, $ip)
    {
        return 1;
    }

}