<?php
namespace App\Daos\User;

use App\Models\User\UserAccountModel;
use App\Supports\QueryHelper;

/**
 * 用户数据访问对象
 *
 * @author zhangzhengkun
 */
class UserDao
{
    /**
     * 查询用户信息
     *
     * @param array $filters
     * @param array $fields
     *
     * @return array
     *
     */
    public function getUserInfo($filters, $fields)
    {
        $qb = UserAccountModel::query();

        $qb = QueryHelper::select($qb, $fields, [
            'id', 'platform', 'open_id', 'nickname', 'avatar', 'mobile', 'arguments', 'created_at', 'updated_at'
        ]);

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'platform'   => ['='],
            'open_id'    => ['=', 'in'],
            'mobile'     => ['=', 'in'],
            'created_at' => ['>', '>=', '<', '<=', '='],
            'updated_at' => ['>', '>=', '<', '<=', '=']
        ]);

        $result = $qb->first();

        return !empty($result) ? $result->toArray() : [];
    }

    /**
     * 查询用户列表信息
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param int $skip
     * @param int $limit
     *
     * @return array
     *
     */
    public function getUserList($filters, $fields, $orderBys, $skip, $limit)
    {
        $qb = UserAccountModel::query();

        $qb = QueryHelper::select($qb, $fields, [
            'id', 'platform', 'open_id', 'nickname', 'avatar', 'mobile', 'arguments', 'created_at', 'updated_at'
        ]);

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'platform'   => ['='],
            'open_id'    => ['=', 'in'],
            'mobile'     => ['=', 'in'],
            'created_at' => ['>', '>=', '<', '<=', '='],
            'updated_at' => ['>', '>=', '<', '<=', '=']
        ]);

        $qb = QueryHelper::orderBy($qb, $orderBys, [
            'id', 'created_at', 'updated_at'
        ]);

        // 总数
        $totalCount = $qb->count();

        if ($limit > 0) {
            $qb = QueryHelper::skipLimit($qb, $skip, $limit);
        }

        $data  = $qb->get();
        $count = $data->count();

        return [
            'total_count' => $totalCount,
            'count'       => $count,
            'data'        => !empty($data) ? $data->toArray() : []
        ];
    }

    /**
     * 创建用户
     *
     * @param string $platform
     * @param string $openId
     * @param string $nickname
     * @param string $avatar
     * @param string|null $mobile
     * @param array|null $arguments
     *
     * @return int
     *
     */
    public function createUser($platform, $openId, $nickname, $avatar, $mobile, $arguments)
    {
        $userId = UserAccountModel::query()->insertGetId([
            'platform'  => $platform,
            'open_id'   => $openId,
            'nickname'  => $nickname,
            'avatar'    => $avatar,
            'mobile'    => $mobile,
            'arguments' => $arguments
        ]);

        return $userId;
    }

    /**
     * 编辑用户信息
     *
     * @param array $filters
     * @param array $data
     *
     * @return int
     *
     */
    public function updateUser($filters, $data)
    {
        $qb = UserAccountModel::query();

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'platform'   => ['='],
            'open_id'    => ['=', 'in'],
            'mobile'     => ['=', 'in'],
            'created_at' => ['>', '>=', '<', '<=', '='],
            'updated_at' => ['>', '>=', '<', '<=', '=']
        ]);

        // 控制只允许被修改的字段
        $data = array_only($data, [
            'nickname', 'avatar', 'mobile', 'arguments'
        ]);

        $result = $qb->update($data);

        return $result;
    }
}