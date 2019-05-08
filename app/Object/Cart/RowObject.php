<?php
namespace App\Object\Cart;

use App\Models\Order\CartModel;
use App\Supports\Platform;
use Illuminate\Support\Facades\Validator;

/***
 * 行项目对象
 *
 * @author Qianc
 */
class RowObject
{
    /**
     * 行项目ID
     *
     * @var integer
     */
    public $id;

    /**
     * 行项目关联菜品ID
     *
     * @var integer
     */
    public $dishId;

    /**
     * 店铺ID
     *
     * @var integer
     */
    public $shopId;

    /**
     * 行项目关联skuId
     *
     * @var integer
     */
    public $skuId;

    /**
     * 行项目数量
     * @var integer
     */
    private $quantity;

    /**
     * 平台
     *
     * @var string
     */
    public $platform;


    /**
     *  加入时间
     *
     * @var datetime
     */
    public $createdAt;


    public function __construct(array $dishInfo)
    {
        $this->id = $dishInfo['id'] ?? '';
        $this->dishId = $dishInfo['item_id'];
        $this->skuId = $dishInfo['sku_id'];
        $this->platform = $dishInfo['platform'] ?? Platform::PLATFORM_WX_PUBLIC;
        $this->quantity = $dishInfo['quantity'];
        $this->shopId = $dishInfo['shop_id'];
        $this->createdAt = $dishInfo['created_at'] ?? date(now());
    }

    /**
     * 修改行项目数量
     *
     * @param $quantity
     * @return bool
     */
    public function modifyQuantity(int $quantity)
    {
        //设置行项目数量
        $this->quantity = $quantity;

        //数据校验
        Validator::make(
            [
                'id' => $this->id,
                'quantity' => $this->quantity
            ],
            [
                'id' => 'required|integer',
                'quantity' => 'required|integer|min:1',
            ],
            [
                'id.required' => '行项目ID不存在',
                'quantity.min' => '修改数量必须大于1',
            ]
        )->validate();

        CartModel::query()->find($this->id)
            ->fill(['quantity' => $this->quantity])
            ->save();

        return true;

    }

    /**
     * 返回购物车行项目数量
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * 修改行项目sku信息
     *
     * @param int $skuId
     * @return bool
     */
    public function modifySku(int $skuId)
    {
        //设置行项目的skuId
        $this->skuId = $skuId;

        //数据校验
        Validator::make(
            [
                'id' => $this->id,
                'sku_id' => $this->skuId
            ],
            [
                'id' => 'required|integer',
                'sku_id' => 'required|integer|min:1',
            ],
            [
                'id.required' => '行项目ID不存在',
                'sku_id.min' => '行项目的skuId必须大于1',
            ]
        )->validate();

        CartModel::query()->find($this->id)
            ->fill([
                'sku_id' => $this->skuId
            ])
            ->save();

        return true;

    }
}