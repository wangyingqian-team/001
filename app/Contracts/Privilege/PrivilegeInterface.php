<?php
namespace App\Contracts\Privilege;

/**
 * 权限相关接口
 *
 * @author zhangzhengkun
 */
interface PrivilegeInterface
{
    /**
     * 创建角色
     *
     * @param string $roleName
     * @param array $privilegeList
     *
     * @return int
     */
    public function createRole($roleName, $privilegeList);

    /**
     * 设置角色的禁启用状态
     *
     * @param int $roleId
     * @param int $status
     *
     * @return int
     */
    public function setRoleStatus($roleId, $status);

    /**
     * 查看角色详情
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getRoleInfo($roleId);
}