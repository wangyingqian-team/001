<?php
namespace App\Services\User;
use App\Contracts\User\UserAccountInterface;

/**
 * 用户账号相关功能
 *
 * @author zhangzhengkun
 */
class UserAccountManager implements UserAccountInterface
{
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
        return 1;
    }

}