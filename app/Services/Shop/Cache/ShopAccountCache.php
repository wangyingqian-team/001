<?php
namespace App\Services\Shop\Cache;

use Illuminate\Support\Facades\Redis;

/**
 * 商家账号缓存处理类
 *
 * @author zhangzhengkun
 */
class ShopAccountCache
{
    // 店铺账号信息缓存键
    const SHOP_ACCOUNT_INFO = 'shopAccount:info';

    // 店铺账号信息缓存时间
    const SHOP_ACCOUNT_INFO_TIME = '3600';

    // 店铺账号登录令牌
    const SHOP_LOGIN_TOKEN = 'shopAccount:token';

    // 店铺账号登录令牌缓存时间
    const SHOP_LOGIN_TOKEN_TIME = '3600';

    // 店铺账号角色缓存键
    const SHOP_ACCOUNT_ROLE = 'shopAccount:role';

    // 店铺账号角色缓存时间
    const SHOP_ACCOUNT_ROLE_TIME = '3610';

    /**
     * 缓存对象
     *
     * @var Object
     */
    protected $redisObj;

    public function __construct()
    {
        $this->redisObj = Redis::connection('shop');
    }

    /**
     * 设置店铺账号信息缓存
     *
     * @param int $accountId
     * @param array $shopAccountInfo
     *
     * @return bool
     *
     */
    public function setShopAccountInfo($accountId, $shopAccountInfo)
    {
        $shopAccountInfoCacheKey = self::SHOP_ACCOUNT_INFO . "_" . $accountId;

        $this->redisObj->set($shopAccountInfoCacheKey, json_encode($shopAccountInfo));

        $this->redisObj->expire($shopAccountInfoCacheKey, self::SHOP_ACCOUNT_INFO_TIME);

        return true;
    }

    /**
     * 获取店铺账号信息缓存
     *
     * @param int $accountId
     *
     * @return array
     *
     */
    public function getShopAccountInfo($accountId)
    {
        $shopAccountInfoCacheKey = self::SHOP_ACCOUNT_INFO . "_" . $accountId;

        $shopAccountInfo = $this->redisObj->get($shopAccountInfoCacheKey);
        if (!empty($shopAccountInfo)) {
            $shopAccountInfo = json_decode($shopAccountInfo, true);
        }

        return $shopAccountInfo;
    }

    /**
     * 删除店铺账号信息缓存
     *
     * @param $accountId
     *
     * @return bool
     *
     */
    public function deleteShopAccountInfo($accountId)
    {
        $shopAccountInfoCacheKey = self::SHOP_ACCOUNT_INFO . "_" . $accountId;

        $this->redisObj->del($shopAccountInfoCacheKey);

        return true;
    }

    /**
     * 设置登录令牌缓存
     *
     * @param string $loginToken
     * @param array $accountInfo
     *
     * @return bool
     *
     */
    public function setLoginTokenCache($loginToken, $accountInfo)
    {
        $shopLoginTokenCacheKey = self::SHOP_LOGIN_TOKEN . "_" . $loginToken;

        $this->redisObj->set($shopLoginTokenCacheKey, json_encode($accountInfo));

        $this->redisObj->expire($shopLoginTokenCacheKey, self::SHOP_LOGIN_TOKEN_TIME);

        return true;
    }

    /**
     * 获取登录令牌缓存
     *
     * @param string $loginToken
     *
     * @return array
     *
     */
    public function getLoginTokenCache($loginToken)
    {
        $shopLoginTokenCacheKey = self::SHOP_LOGIN_TOKEN . "_" . $loginToken;

        $accountInfo = $this->redisObj->get($shopLoginTokenCacheKey);

        if (!empty($accountInfo)) {
            $accountInfo = json_decode($accountInfo, true);
        }

        return $accountInfo;
    }

    /**
     * 设置店铺账号角色缓存
     *
     * @param int $accountId
     * @param int $roleId
     *
     * @return bool
     *
     */
    public function setAccountRoleCache($accountId, $roleId)
    {
        $shopAccountRoleCacheKey = self::SHOP_ACCOUNT_ROLE . "_" . $accountId;

        $this->redisObj->set($shopAccountRoleCacheKey, $roleId);

        $this->redisObj->expire($shopAccountRoleCacheKey, self::SHOP_ACCOUNT_ROLE_TIME);

        return true;
    }

    /**
     * 获取店铺账号角色缓存
     *
     * @param int $accountId
     *
     * @return int
     *
     */
    public function getAccountRoleCache($accountId)
    {
        $shopAccountRoleCacheKey = self::SHOP_ACCOUNT_ROLE . "_" . $accountId;

        return $this->redisObj->get($shopAccountRoleCacheKey);
    }
}