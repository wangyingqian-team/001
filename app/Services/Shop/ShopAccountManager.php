<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopAccountInterface;

/**
 * 商家账号相关功能
 *
 * @author zhangzhengkun
 */
class ShopAccountManager implements ShopAccountInterface
{
    /**
     * 注册商家账号
     *
     * @param string $account
     * @param string $password
     *
     * @return int
     */
    public function registerShopAccount($account, $password)
    {
        return 1;
    }

    /**
     * 商家账号登录
     *
     * @param string $account
     * @param string $password
     *
     * @return bool
     */
    public function loginShopAccount($account, $password)
    {
        return 1;
    }

}