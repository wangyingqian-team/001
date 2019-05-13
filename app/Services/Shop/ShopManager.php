<?php
namespace App\Services\Shop;

use App\Contracts\Shop\ShopInterface;
use App\Daos\Shop\ShopDao;
use App\Exceptions\IllegalArgumentException;
use Illuminate\Support\Facades\Validator;

/**
 * 商家店铺相关功能
 *
 * @author zhangzhengkun
 */
class ShopManager implements ShopInterface
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
     * 申请商家入驻
     *
     * @param int $accountId
     * @param string $name
     * @param string $description
     * @param string $sellerName
     * @param string $sellerTel
     * @param string $address
     * @param string $logo
     *
     * @return int
     *
     */
    public function shopEnterApply($accountId, $name, $description, $sellerName, $sellerTel, $address, $logo)
    {
        // 基础数据校验
        Validator::make([
            'account_id'  => $accountId,
            'name'        => $name,
            'description' => $description,
            'seller_name' => $sellerName,
            'seller_tel'  => $sellerTel,
            'address'     => $address,
            'logo'        => $logo
        ], [
            'account_id'  => 'required|integer',
            'name'        => 'required|string|max:50',
            'description' => 'required|string|max:1000',
            'seller_name' => 'required|string|max:50',
            'seller_tel'  => 'required|string|max:50',
            'logo'        => 'required|string|max:255'
        ], [
            'required' => ':attribute不能为空',
            'integer'  => ':attribute必须是整数',
            'max'      => ':attribute的长度不能超过:max个字符'
        ], [
            'account_id'  => '店铺账号编号',
            'name'        => '店铺名称',
            'description' => '店铺描述',
            'seller_name' => '店主姓名',
            'seller_tel'  => '店主联系电话',
            'address'     => '店铺地址',
            'logo'        => '店铺logo'
        ])->validate();

        // 店铺账号校验
        $accountInfo = $this->shopDao->getShopAccountInfo(['id' => $accountId], ['id', 'is_valid']);
        if (empty($accountInfo)) {
            throw new IllegalArgumentException('店铺账号不存在！');
        }
        if ($accountInfo['is_valid'] != 1) {
            throw new IllegalArgumentException('店铺账号已经失效！');
        }

        // 创建店铺并且绑定商家账号
        return $this->shopDao->addShop($accountId, $name, $description, $sellerName, $sellerTel, $address, $logo);
    }


}