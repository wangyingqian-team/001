<?php
namespace App\Daos\Shop;

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
}