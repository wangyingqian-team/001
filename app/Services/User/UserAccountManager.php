<?php
namespace App\Services\User;

use App\Contracts\User\UserAccountInterface;
use App\Daos\User\UserDao;
use App\Supports\Platform;
use Illuminate\Support\Facades\Validator;

/**
 * 用户账号相关功能
 *
 * @author zhangzhengkun
 */
class UserAccountManager implements UserAccountInterface
{
    /**
     * 数据访问对象
     *
     * @var UserDao
     */
    protected $userDao;

    public function __construct(UserDao $userDao)
    {
        $this->userDao = $userDao;
    }

    /**
     * 根据指定平台的openid获取用户id
     *
     * @param string $openId
     * @param string $platform
     *
     * @return int
     */
    public function getUserIdByOpenId($openId, $platform)
    {
        // 数据校验
        Validator::make([
            'open_id'  => $openId,
            'platform' => $platform
        ], [
            'open_id'  => 'required|string|max:50',
            'platform' => 'required|in:' . implode(',', Platform::PLATFORMS),
        ], [
            'required' => ':attribute不能为空',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法'
        ], [
            'open_id'  => '用户open_id',
            'platform' => '平台参数'
        ])->validate();

        $filters = [
            'open_id'  => $openId,
            'platform' => $platform
        ];

        $fields   = ['id'];

        $userInfo = $this->userDao->getUserInfo($filters, $fields);

        return !empty($userInfo['id']) ? $userInfo['id'] : 0;
    }

    /**
     * 获取指定用户的基本信息
     *
     * @param int $userId
     * @param array $fields
     *
     * @return array
     */
    public function getUserInfo($userId, $fields)
    {
        Validator::make([
            'user_id'  => $userId,
            'fields'   => $fields
        ], [
            'user_id'  => 'required|integer|min:1',
            'fields'   => 'required|array',
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须为整数',
            'min'      => ':attribute必须是正整数',
            'array'    => ':attribute必须是数组格式'
        ], [
            'user_id'  => '用户编号',
            'fields'   => '用户属性列表'
        ])->validate();

        return $this->userDao->getUserInfo([
            'id' => $userId
        ], $fields);
    }

    /**
     * 获取用户列表信息
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param int $skip
     * @param int $limit
     *
     * @return array
     */
    public function getUserList($filters, $fields, $orderBys = [], $skip = 0, $limit = 20)
    {
        return $this->userDao->getUserList($filters, $fields, $orderBys, $skip, $limit);
    }


    /**
     * 注册用户账号
     *
     * @param string $platform
     * @param string $openId
     * @param string $nickname
     * @param string $avatar
     * @param string|null $mobile
     * @param array|null $arguments
     *
     * @return int
     */
    public function registerUserAccount($platform, $openId, $nickname, $avatar, $mobile = null, $arguments = null)
    {
        // 数据校验
        Validator::make([
            'platform'  => $platform,
            'open_id'   => $openId,
            'nickname'  => $nickname,
            'avatar'    => $avatar,
            'mobile'    => $mobile,
            'arguments' => $arguments
        ], [
            'platform'  => 'required|in:' . implode(',', Platform::PLATFORMS),
            'open_id'   => 'required|string|max:50',
            'nickname'  => 'required|string|max:50',
            'avatar'    => 'required|string|max:255',
            'mobile'    => 'nullable|mobile',
            'arguments' => 'nullable|array'
        ], [
            'required' => ':attribute不能为空',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法',
            'mobile'   => ':attribute必须是合法的手机号',
            'array'    => ':attribute必须是数组格式',
        ], [
            'platform'  => '平台参数',
            'open_id'   => '用户open_id',
            'nickname'  => '用户昵称',
            'avatar'    => '用户头像',
            'mobile'    => '用户手机号码',
            'arguments' => '用户扩展参数'
        ])->validate();

        $userId = $this->userDao->createUser($platform, $openId, $nickname, $avatar, $mobile, $arguments);

        return $userId;
    }

    /**
     * 修改用户基本信息
     *
     * @param int $userId
     * @param string $nickname
     * @param string $avatar
     * @param string|null $mobile
     * @param array|null
     *
     * @return int
     */
    public function updateUserInfo($userId, $nickname, $avatar, $mobile = null, $arguments = null)
    {
        // 数据校验
        Validator::make([
            'user_id'   => $userId,
            'nickname'  => $nickname,
            'avatar'    => $avatar,
            'mobile'    => $mobile,
            'arguments' => $arguments
        ], [
            'user_id'   => 'required|integer|min:1',
            'nickname'  => 'required|string|max:50',
            'avatar'    => 'required|string|max:255',
            'mobile'    => 'nullable|mobile',
            'arguments' => 'nullable|array'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute必须是正整数',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法',
            'mobile'   => ':attribute必须是合法的手机号',
            'array'    => ':attribute必须是数组格式',
        ], [
            'user_id'   => '用户编号',
            'nickname'  => '用户昵称',
            'avatar'    => '用户头像',
            'mobile'    => '用户手机号码',
            'arguments' => '用户扩展参数'
        ])->validate();

        $filters = [
            'id' => $userId
        ];

        $data = [
            'nickname'  => $nickname,
            'avatar'    => $avatar,
            'mobile'    => $mobile,
            'arguments' => $arguments
        ];

        $result = $this->userDao->updateUser($filters, $data);

        return $result;
    }


}