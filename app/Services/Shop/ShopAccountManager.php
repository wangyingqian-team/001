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
     * @param int $type
     * @param int $roleId
     * @param string $name
     * @param string $mobile
     * @param string $email
     * @param int $shopId
     *
     * @return int
     */
    public function registerShopAccount($account, $password, $type, $roleId, $name, $mobile, $email, $shopId = 0)
    {
        // 基础数据校验
        Validator::make([
            'shop_id'  => $shopId,
            'account'  => $account,
            'password' => $password,
            'type'     => $type,
            'role_id'  => $roleId,
            'name'     => $name,
            'mobile'   => $mobile,
            'email'    => $email
        ], [
            'shop_id'  => 'required|integer',
            'account'  => 'required|string|min:3|max:20',
            'password' => 'required|string|min:6|max:20',
            'type'     => 'required|integer|in:' . implode(',', ShopConst::ACCOUNT_TYPES),
            'role_id'  => 'required|integer',
            'name'     => 'required|string|min:1|max:20',
            'mobile'   => 'required|mobile',
            'email'    => 'required|email'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute的长度不能超过:max个字符',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法',
            'mobile'   => ':attribute必须是合法的手机号',
            'email'    => ':attribute必须是合法的电子邮箱格式',
        ], [
            'shop_id'  => '店铺编号',
            'account'  => '商家账号',
            'password' => '商家密码',
            'type'     => '账号类型',
            'role_id'  => '角色编号',
            'name'     => '商家姓名',
            'mobile'   => '商家联系电话',
            'email'    => '商家邮箱'
        ])->validate();

        // 校验账号密码格式的合法性
        $this->checkAccountAndPassword($account, $password);

        // 检查账号是否被占用
        $this->checkAccountOccupied($account);

        // 密码哈希处理
        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));

        // 添加账号
        return $this->shopDao->addShopAccount($account, $password, $shopId, $type, $roleId, $name, $mobile, $email);
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
        // 基础数据校验
        Validator::make([
            'account_id'  => $accountId,
            'type'        => $type,
            'role_id'     => $roleId,
            'name'        => $name,
            'mobile'      => $mobile,
            'email'       => $email
        ], [
            'account_id'  => 'required|integer',
            'type'        => 'required|integer|in:' . implode(',', ShopConst::ACCOUNT_TYPES),
            'role_id'     => 'required|integer',
            'name'        => 'required|string|min:1|max:20',
            'mobile'      => 'required|mobile',
            'email'       => 'required|email'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute的长度不能超过:max个字符',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法',
            'mobile'   => ':attribute必须是合法的手机号',
            'email'    => ':attribute必须是合法的电子邮箱格式',
        ], [
            'account_id'  => '商家账号编号',
            'type'        => '账号类型',
            'role_id'     => '角色编号',
            'name'        => '商家姓名',
            'mobile'      => '商家联系电话',
            'email'       => '商家邮箱'
        ])->validate();

        // 修改商家账号基本信息
        return $this->shopDao->updateShopAccount([
            'id' => $accountId
        ], [
            'type'    => $type,
            'role_id' => $roleId,
            'name'    => $name,
            'mobile'  => $mobile,
            'email'   => $email
        ]);
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
        // 基础数据校验
        Validator::make([
            'account_id'      => $accountId,
            'origin_password' => $originPassword,
            'new_password'    => $newPassword
        ], [
            'account_id'      => 'required|integer',
            'origin_password' => 'required|string|min:6|max:20',
            'new_password'    => 'required|string|min:6|max:20'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute的长度不能超过:max个字符',
            'max'      => ':attribute的长度不能超过:max个字符'
        ], [
            'account_id'      => '商家账户编号',
            'origin_password' => '旧密码',
            'new_password'    => '新密码'
        ])->validate();

        // 检查旧密码是否输入正确
        $accountInfo = $this->shopDao->getShopAccountInfo(['id' => $accountId, 'is_valid' => 1], ['password']);
        if (empty($accountInfo)) {
            throw new IllegalArgumentException('店铺账号不存在或者店铺账号失效！');
        }

        if (!password_verify($originPassword, $accountInfo['password'])) {
            throw new IllegalArgumentException('旧密码填写错误，请重新填写！');
        }

        // 新密码校验
        $this->checkAccountAndPassword(null, $newPassword);

        // 新密码哈希处理
        $newPassword = password_hash($newPassword, PASSWORD_BCRYPT, array('cost' => 10));

        // 修改密码
        $this->shopDao->updateShopAccount([
            'id' => $accountId
        ], [
            'password' => $newPassword
        ]);

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
     * @param int $accountId
     * @param array $fields
     *
     * @return array
     *
     */
    public function getShopAccountInfo($accountId, $fields)
    {
        // 基础数据校验
        Validator::make([
            'account_id' => $accountId,
            'fields'     => $fields
        ], [
            'account_id' => 'required|integer|min:1',
            'fields'     => 'required|array|in:'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须为整数',
            'min'      => ':attribute必须是正整数',
            'array'    => ':attribute必须是数组格式'
        ], [
            'account_id'  => '商家账号编号',
            'fields'      => '商家账号属性列表'
        ])->validate();

        // 查询商家账号基本信息
        return $this->shopDao->getShopAccountInfo([
            'id' => $accountId
        ], $fields);
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
        return $this->shopDao->getShopAccountList($filters, $fields, $orderBys, $skip, $limit);
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
        // 账号不为空时，检查账号
        if (!empty($account)) {
            if (is_numeric($account)) {
                throw new IllegalArgumentException('登录账号不能全为数字');
            }

            if (!preg_match('/^[^\x00-\x2d^\x2f^\x3a-\x3f]+$/i', trim($account))) {
                throw new IllegalArgumentException('该登录账号包含非法字符');
            }
        }

        // 密码不为空时，检查密码
        if (!empty($password)) {
            if (is_numeric($password)) {
                throw new IllegalArgumentException('密码不能为纯数字');
            }

            if (preg_match("/^[a-z]*$/i", trim($password))) {
                throw new IllegalArgumentException('密码不能为纯字母');
            }
        }

        return true;
    }

    /**
     * 检查账号被占用情况
     *
     * @param string $account
     *
     * @return bool
     *
     */
    private function checkAccountOccupied($account)
    {
        $accountInfo = $this->shopDao->getShopAccountInfo(['account' => $account], ['id']);

        if (!empty($accountInfo)) {
            throw new IllegalArgumentException('该账号已经被占用！');
        }

        return true;
    }

}