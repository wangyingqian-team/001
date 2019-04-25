<?php
namespace App\Models\Privilege;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    /**
     * 数据表名
     *
     * @var string
     */
    protected $table = 'role';

    /**
     * 关联角色对应的权限
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     *
     */
    public function privilege()
    {
        return $this->hasOne(RolePrivilegeModel::class, 'role_id');
    }
}