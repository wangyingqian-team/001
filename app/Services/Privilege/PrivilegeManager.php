<?php
namespace App\Services\Privilege;

use App\Contracts\Privilege\PrivilegeInterface;
use App\Daos\Privilege\PrivilegeDao;

/**
 * 角色与权限相关功能
 *
 * @author zhangzhengkun
 */
class PrivilegeManager implements PrivilegeInterface
{
    /**
     * 数据访问对象
     *
     * @var PrivilegeDao
     */
    protected $privilegeDao;

    public function __construct(PrivilegeDao $privilegeDao)
    {
        $this->privilegeDao = $privilegeDao;
    }

    /**
     * 创建角色
     *
     * @param string $roleName
     * @param array $privilegeList
     *
     * @return int
     *
     * @throws \Throwable
     */
    public function createRole($roleName, $privilegeList)
    {
        // todo 数据校验

        return $this->privilegeDao->createRole($roleName, $privilegeList);
    }

    /**
     * 更新角色
     *
     * @param int $roleId
     * @param string $roleName
     * @param array $privilegeList
     *
     * @return bool
     */
    public function updateRole($roleId, $roleName, $privilegeList)
    {
        // todo 数据校验

        return $this->privilegeDao->updateRole($roleId, $roleName, $privilegeList);
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
        // todo 数据校验

        return $this->privilegeDao->setRoleStatus($roleId, $status);
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
        // todo 数据校验

        return $this->privilegeDao->getRoleInfo($roleId);
    }

}