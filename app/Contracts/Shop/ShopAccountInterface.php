<?php
namespace App\Contracts\Shop;

/**
 * 商家账号相关接口
 *
 * @author zhangzhengkun
 */
interface ShopAccountInterface
{
    /**
     * 注册商家账号
     *
     * @param string $account
     * @param string $password
     *
     * @return int
     */
    public function registerShopAccount($account, $password);

    /**
     * 商家账号登录
     *
     * @param string $account
     * @param string $password
     *
     * @return bool
     */
    public function loginShopAccount($account, $password);
}