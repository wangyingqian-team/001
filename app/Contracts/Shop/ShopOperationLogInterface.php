<?php
namespace App\Contracts\Shop;

/**
 * 商家操作日志相关接口
 *
 * @author zhangzhengkun
 */
interface ShopOperationLogInterface
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
    public function addOperationLog($shopId, $sellerAccount, $content, $route, $ip);
}