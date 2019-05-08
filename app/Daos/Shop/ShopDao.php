<?php
namespace App\Daos\Shop;

use App\Models\Shop\ShopAccountModel;
use App\Models\Shop\ShopOperationLogModel;
use App\Supports\QueryHelper;

/**
 * 商家相关数据操作对象
 *
 * @author zhangzhengkun
 */
class ShopDao
{
    /**
     * 添加商家店铺操作日志
     *
     * @param int $shopId
     * @param string $sellerAccount
     * @param string $content
     * @param string $route
     * @param string $ip
     *
     * @return int
     *
     */
    public function addShopOperationLog($shopId, $sellerAccount, $content, $route, $ip)
    {
        $logId = ShopOperationLogModel::query()->insertGetId([
            'shop_id'        => $shopId,
            'seller_account' => $sellerAccount,
            'content'        => $content,
            'route'          => $route,
            'ip'             => $ip
        ]);

        return $logId;
    }

    /**
     * 获取商家店铺操作日志列表
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
    public function getShopOperationLogList($filters, $fields, $orderBys, $skip, $limit)
    {
        $qb = ShopOperationLogModel::query();

        $qb = QueryHelper::select($qb, $fields, [
            'id', 'shop_id', 'seller_account', 'content', 'route', 'ip', 'created_at', 'updated_at'
        ]);

        $qb = QueryHelper::filter($qb, $filters, [
            'id'             => ['=', 'in'],
            'shop_id'        => ['=', 'in'],
            'seller_account' => ['=', 'in'],
            'route'          => ['='],
            'ip'             => ['=', 'in'],
            'created_at'     => ['>', '>=', '<', '<=', '='],
            'updated_at'     => ['>', '>=', '<', '<=', '=']
        ]);

        $qb = QueryHelper::orderBy($qb, $orderBys, [
            'id', 'ip', 'created_at', 'updated_at'
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
     * 添加商家账号
     *
     * @param string $account
     * @param string $password
     * @param int $shopId
     * @param int $type
     * @param int $roleId
     * @param string $name
     * @param string $mobile
     * @param string $email
     *
     * @return int
     *
     */
    public function addShopAccount($account, $password, $shopId, $type, $roleId, $name, $mobile, $email)
    {
        $accountId = ShopAccountModel::query()->insertGetId(
            [
                'shop_id'  => $shopId,
                'account'  => $account,
                'password' => $password,
                'type'     => $type,
                'role_id'  => $roleId,
                'name'     => $name,
                'mobile'   => $mobile,
                'email'    => $email
            ]
        );

        return $accountId;
    }

    /**
     * 修改商家账号信息
     *
     * @param $filters
     * @param $data
     *
     * @return int
     *
     */
    public function updateShopAccount($filters, $data)
    {
        $qb = ShopAccountModel::query();

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'shop_id'    => ['=']
        ]);

        // 控制只允许被修改的字段（注意：账号不可被修改）
        $data = array_only($data, [
            'shop_id', 'type', 'role_id', 'is_valid', 'password', 'name', 'mobile', 'email'
        ]);

        $result = $qb->update($data);

        return $result;
    }

    /**
     * 查询单个商家账号信息
     *
     * @param array $filters
     * @param array $fields
     *
     * @return array
     *
     */
    public function getShopAccountInfo($filters, $fields)
    {
        $qb = ShopAccountModel::query();

        $qb = QueryHelper::select($qb, $fields, [
            'id', 'shop_id', 'type', 'role_id', 'is_valid', 'account', 'password', 'name', 'mobile', 'email', 'created_at', 'updated_at'
        ]);

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'shop_id'    => ['='],
            'type'       => ['='],
            'account'    => ['=', 'in'],
            'is_valid'   => ['=', 'in'],
            'created_at' => ['>', '>=', '<', '<=', '='],
            'updated_at' => ['>', '>=', '<', '<=', '=']
        ]);

        $result = $qb->first();

        return !empty($result) ? $result->toArray() : [];
    }

    /**
     * 查询商家账号列表信息
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
    public function getShopAccountList($filters, $fields, $orderBys, $skip, $limit)
    {
        $qb = ShopAccountModel::query();

        $qb = QueryHelper::select($qb, $fields, [
            'id', 'shop_id', 'type', 'role_id', 'is_valid', 'account', 'password', 'name', 'mobile', 'email', 'created_at', 'updated_at'
        ]);

        $qb = QueryHelper::filter($qb, $filters, [
            'id'         => ['=', 'in'],
            'shop_id'    => ['='],
            'type'       => ['='],
            'account'    => ['=', 'in'],
            'is_valid'   => ['=', 'in'],
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
}