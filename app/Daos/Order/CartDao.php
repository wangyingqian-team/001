<?php
namespace App\Daos\Order;

use App\Daos\Query\CommonDao;
use App\Models\Order\CartModel;

class CartDao extends CommonDao
{
    /**
     * 允许的字段
     *
     * @var array
     */
    protected static $easyFields = [
        // cart
        "id", "user_id", "shop_id", "dish_id", "sku_id", "quantity", "platform", "created_at", "updated_at",
    ];


    /**
     * 可供查询的字段
     *
     * @var array
     */
    protected static $easyFilters = [
        'id' => ['=', 'in', 'not in'],
        'user_id' => ['=', '!='],
        'shop_id' => ['=', 'in'],
        'dish_id' => ['=', 'in'],
        'sku_id' => ['=', 'in'],
        'platform' => ['=', 'in', 'not in'],
    ];


    protected static $easyOrderBys = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * 创建查询对象
     *
     * @return mixed
     */
    protected static function createQuery()
    {
        return CartModel::query();
    }
}