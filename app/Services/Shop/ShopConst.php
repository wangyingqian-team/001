<?php
namespace App\Services\Shop;

/**
 * 商家相关常量类
 *
 * @author zhangzhengkun
 */
class ShopConst
{
    /**
     * 账号类型：店主
     */
    const ACCOUNT_TYPE_SHOPKEEPER = 1;

    /**
     * 账号类型：店员
     */
    const ACCOUNT_TYPE_SALESCLERK = 2;

    /**
     * 账号类型
     */
    const ACCOUNT_TYPES = [
        self::ACCOUNT_TYPE_SHOPKEEPER,
        self::ACCOUNT_TYPE_SALESCLERK
    ];
}