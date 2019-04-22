<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopInterface;

/**
 * 商家店铺相关功能
 *
 * @author zhangzhengkun
 */
class ShopManager implements ShopInterface
{
    /**
     * 申请商家入驻
     *
     * @param int $accountId
     * @param string $name
     * @param string $mobile
     * @param string $email
     * @param string $description
     * @param string $logo
     *
     * @return int
     */
    public function shopEnterApply($accountId, $name, $mobile, $email, $description, $logo)
    {
        return 1;
    }

}