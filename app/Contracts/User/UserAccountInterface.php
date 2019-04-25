<?php
namespace App\Contracts\User;

/**
 * 用户账号相关接口
 *
 * @author zhangzhengkun
 */
interface UserAccountInterface
{
    /**
     * 根据指定平台的openid获取用户id
     *
     * @param string $openId
     * @param string $platform
     *
     * @return int
     */
    public function getUserIdByOpenId($openId, $platform);

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
    public function registerUserAccount($platform, $openId, $nickname, $avatar, $mobile = null, $arguments = null);

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
    public function updateUserInfo($userId, $nickname, $avatar, $mobile = null, $arguments = null);
}