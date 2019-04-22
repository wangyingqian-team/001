<?php
namespace App\Services\Privilege;

use App\Contracts\Privilege\PrivilegeInterface;

/**
 * 角色与权限相关功能
 *
 * @author zhangzhengkun
 */
class PrivilegeManager implements PrivilegeInterface
{
    /**
     * 创建角色
     *
     * @param string $roleName
     * @param array $privilegeList
     *
     * @return int
     */
    public function createRole($roleName, $privilegeList)
    {
        return 1;
    }

    /**
     * 设置角色的禁启用状态
     *
     * @param int $roleId
     * @param int $status
     *
     * @return int
     */
    public function setRoleStatus($roleId, $status)
    {
        return 1;
    }

    /**
     * 查看角色详情
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getRoleInfo($roleId)
    {
        return [];
    }

}