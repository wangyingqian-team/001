<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopOperationLogInterface;
use App\Daos\Shop\ShopDao;
use Illuminate\Support\Facades\Validator;

/**
 * 商家操作日志相关功能
 *
 * @author zhangzhengkun
 */
class ShopOperationLogManager implements ShopOperationLogInterface
{
    /**
     * 数据访问对象
     *
     * @var ShopDao
     */
    protected $shopDao;

    public function __construct(ShopDao $shopDao)
    {
        $this->shopDao = $shopDao;
    }

    /**
     * 添加商家操作日志
     *
     * @param int $shopId 店铺id
     * @param string $sellerAccount 操作账号
     * @param string $content 操作内容
     * @param string $route 操作路由
     * @param string $ip IP
     *
     * @return int
     */
    public function addShopOperationLog($shopId, $sellerAccount, $content, $route, $ip)
    {
        // 数据校验
        Validator::make([
            'shop_id'        => $shopId,
            'seller_account' => $sellerAccount,
            'content'        => $content,
            'route'          => $route,
            'ip'             => $ip
        ], [
            'shop_id'        => 'required|integer|min:1',
            'seller_account' => 'required|string|max:20',
            'content'        => 'required|string|max:1000',
            'route'          => 'required|string|max:100',
            'ip'             => 'required|ip'
        ], [
            'required' => ':attribute不能为空',
            'max'      => ':attribute的长度不能超过:max个字符',
            'in'       => ':attribute的取值不合法'
        ], [
            'shop_id'        => '店铺编号',
            'seller_account' => '操作用户账号',
            'content'        => '操作内容',
            'route'          => '操作内容',
            'ip'             => '操作者的IP地址'
        ])->validate();

        $logId = $this->shopDao->addShopOperationLog($shopId, $sellerAccount, $content, $route, $ip);

        return $logId;
    }

    /**
     * 查询商家操作日志列表
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param int $skip
     * @param int $limit
     *
     * @return array
     */
    public function getShopOperationLogList($filters, $fields, $orderBys = [], $skip = 0, $limit = 50)
    {
        return $this->shopDao->getShopOperationLogList($filters, $fields, $orderBys, $skip, $limit);
    }


}