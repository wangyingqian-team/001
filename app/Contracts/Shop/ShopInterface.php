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
     * @param string $mobile
     * @param string $email
     * @param string $description
     * @param string $logo
     *
     * @return int
     */
    public function shopEnterApply($accountId, $name, $mobile, $email, $description, $logo);
}