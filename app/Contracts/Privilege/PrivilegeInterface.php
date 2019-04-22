<?php
namespace App\Contracts\Privilege;

/**
 * 角色与权限相关接口
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
     * 更新角色
     *
     * @param int $roleId
     * @param string $roleName
     * @param array $privilegeList
     *
     * @return bool
     */
    public function updateRole($roleId, $roleName, $privilegeList);

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