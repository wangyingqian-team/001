<?php
namespace App\Services\Privilege;

use App\Contracts\Privilege\PrivilegeInterface;
use App\Daos\Privilege\PrivilegeDao;
use Illuminate\Support\Facades\Validator;

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
        // 数据校验
        Validator::make([
            'role_name'      => $roleName,
            'privilege_list' => $privilegeList
        ], [
            'role_name'      => 'required|string|max:50',
            'privilege_list' => 'required|array'
        ], [
            'required' => ':attribute不能为空',
            'max'      => ':attribute的长度不能超过:max个字符',
            'array'    => ':attribute必须是数组格式'
        ], [
            'role_name'      => '角色名称',
            'privilege_list' => '权限列表'
        ])->validate();

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
        // 数据校验
        Validator::make([
            'role_id'        => $roleId,
            'role_name'      => $roleName,
            'privilege_list' => $privilegeList
        ], [
            'role_id'        => 'required|integer|min:1',
            'role_name'      => 'required|string|max:50',
            'privilege_list' => 'required|array'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute必须是正数',
            'max'      => ':attribute的长度不能超过:max个字符',
            'array'    => ':attribute必须是数组格式'
        ], [
            'role_id'        => '角色编号',
            'role_name'      => '角色名称',
            'privilege_list' => '权限列表'
        ])->validate();

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
        // 数据校验
        Validator::make([
            'role_id' => $roleId,
            'status'  => $status
        ], [
            'role_id' => 'required|integer|min:1',
            'status'  => 'required|integer|in:0,1',
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute必须是正数',
            'in'       => ':attribute的取值不合法'
        ], [
            'role_id'=> '角色编号',
            'status' => '角色启用状态'
        ])->validate();

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
        // 数据校验
        Validator::make([
            'role_id' => $roleId
        ], [
            'role_id' => 'required|integer|min:1'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'min'      => ':attribute必须是正数'
        ], [
            'role_id' => '角色编号'
        ])->validate();

        $fields = [
            'id', 'name', 'status', 'created_at', 'updated_at',
            'privilege.id', 'privilege.privilege'
        ];

        $roleInfo = $this->privilegeDao->getRoleInfo($roleId, $fields);

        // 处理权限列表数据
        if (!empty($roleInfo['privilege'])) {
            $roleInfo['privilege_list'] = array_filter(
                explode(',', $roleInfo['privilege'])
            );
        }

        return $roleInfo;
    }

}