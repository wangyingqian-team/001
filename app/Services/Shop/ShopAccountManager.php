<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopAccountInterface;
use App\Daos\Shop\ShopDao;
use App\Exceptions\IllegalArgumentException;
use App\Services\Shop\Cache\ShopAccountCache;
use Illuminate\Support\Facades\Validator;

/**
 * 商家账号相关功能
 *
 * @author zhangzhengkun
 */
class ShopAccountManager implements ShopAccountInterface
{
    /**
     * 数据访问对象
     *
     * @var ShopDao
     */
    protected $shopDao;

    /**
     * 缓存处理对象
     *
     * @var ShopAccountCache
     */
    protected $accountCache;

    public function __construct(ShopDao $shopDao, ShopAccountCache $accountCache)
    {
        $this->shopDao = $shopDao;
        $this->accountCache = $accountCache;
    }

    /**
     * 注册商家账号
     *
     * @param string $account
     * @param string $password
     * @param int $shopId
     * @param int $type
     * @param int $roleId
     * @param string $name
     * @param string $mobile
     * @param string $email
     *
     * @return int
     */
    public function registerShopAccount($account, $password, $shopId, $type, $roleId, $name, $mobile, $email)
    {
        return 1;
    }

    /**
     * 修改商家账号信息
     *
     * @param int $accountId
     * @param int $type
     * @param int $roleId
     * @param string $name
     * @param string $mobile
     * @param string $email
     *
     * @return int
     *
     */
    public function updateShopAccount($accountId, $type, $roleId, $name, $mobile, $email)
    {
        return 1;
    }

    /**
     * 修改密码
     *
     * @param int $accountId
     * @param string $originPassword
     * @param string $newPassword
     *
     * @return bool
     *
     */
    public function updatePassword($accountId, $originPassword, $newPassword)
    {
        return true;
    }

    /**
     * 商家账号登录
     *
     * @param string $account
     * @param string $password
     *
     * @return array
     */
    public function loginShopAccount($account, $password)
    {
        return [];
    }

    /**
     * 检查账号是否登录
     *
     * @param string $shopToken 登录token
     *
     * @return array
     *
     */
    public function checkIfLogin($shopToken)
    {
        return [];
    }

    /**
     * 将账号关联到指定店铺
     *
     * @param int $accountId
     * @param int $shopId
     *
     * @return int
     *
     */
    public function relateAccountWithShop($accountId, $shopId)
    {
        return 1;
    }

    /**
     * 查询某个账号的基本信息
     *
     * @param int $shopId
     * @param array $fields
     *
     * @return array
     *
     */
    public function getShopAccountInfo($shopId, $fields)
    {
        return [];
    }

    /**
     * 查下账号列表信息
     *
     * @param $filters
     * @param $fields
     * @param array $orderBys
     * @param int $skip
     * @param int $limit
     *
     * @return array
     *
     */
    public function getShopAccountList($filters, $fields, $orderBys = [], $skip = 0, $limit = 20)
    {
        return [];
    }

    /**
     * 检查账号密码合法性
     *
     * @param string $account
     * @param string $password
     *
     * @return bool
     *
     */
    private function checkAccountAndPassword($account, $password)
    {
        if (is_numeric($account)) {
            throw new IllegalArgumentException('登录账号不能全为数字');
        }

        if (!preg_match('/^[^\x00-\x2d^\x2f^\x3a-\x3f]+$/i', trim($account))) {
            throw new IllegalArgumentException('该登录账号包含非法字符');
        }

        if (is_numeric($password)) {
            throw new IllegalArgumentException('密码不能为纯数字');
        }

        if (preg_match("/^[a-z]*$/i", trim($password))) {
            throw new IllegalArgumentException('密码不能为纯字母');
        }

        return true;
    }

}