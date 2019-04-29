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
     * @param int $shopId
     * @param int $type
     * @param int $roleId
     * @param string $name
     * @param string $mobile
     * @param string $email
     *
     * @return int
     */
    public function registerShopAccount($account, $password, $shopId, $type, $roleId, $name, $mobile, $email);

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
    public function updateShopAccount($accountId, $type, $roleId, $name, $mobile, $email);

    #/**
    # * 重置密码
    # *
    # * @param int $accountId
    # * @param string $password
    # *
    # * @return bool
    # *
    # */
    # public function setPassword($accountId, $password);

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
    public function updatePassword($accountId, $originPassword, $newPassword);

    /**
     * 商家账号登录
     *
     * @param string $account
     * @param string $password
     *
     * @return array
     */
    public function loginShopAccount($account, $password);

    /**
     * 检查账号是否登录
     *
     * @param string $shopToken 登录token
     *
     * @return array
     *
     */
    public function checkIfLogin($shopToken);

    /**
     * 将账号关联到指定店铺
     *
     * @param int $accountId
     * @param int $shopId
     *
     * @return int
     *
     */
    public function relateAccountWithShop($accountId, $shopId);

    /**
     * 查询某个账号的基本信息
     *
     * @param int $shopId
     * @param array $fields
     *
     * @return array
     *
     */
    public function getShopAccountInfo($shopId, $fields);

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
    public function getShopAccountList($filters, $fields, $orderBys = [], $skip = 0, $limit = 20);
}