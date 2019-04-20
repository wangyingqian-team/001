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
}