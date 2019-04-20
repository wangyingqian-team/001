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
     * @param string $privilegeList
     *
     * @return int
     */
    public function createRole($roleName, $privilegeList);

    /**
     * 查看角色详情
     *
     * @param int $roleId
     *
     * @return array
     */
    public function getRoleInfo($roleId);
}