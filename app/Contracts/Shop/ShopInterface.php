<?php
namespace App\Contracts\Shop;

/**
 * 商家店铺相关接口
 *
 * @author zhangzhengkun
 */
interface ShopInterface
{
    /**
     * 申请商家入驻
     *
     * @param int $accountId
     * @param string $name
     * @param string $description
     * @param string $sellerName
     * @param string $sellerTel
     * @param string $address
     * @param string $logo
     *
     * @return int
     *
     */
    public function shopEnterApply($accountId, $name, $description, $sellerName, $sellerTel, $address, $logo);
}