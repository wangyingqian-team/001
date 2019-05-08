<?php

namespace App\Object\Cart;

use App\Daos\Order\CartDao;
use App\Models\Order\CartModel;
use Liviator\Exception\ResourceNotFoundException;
use Liviator\Exception\UnsupportedOperationException;
use Wangyingqian\AliChat\Kernel\Config;

/***
 * 购物车对象
 *
 * @author Qianc
 */
class CartObject
{
    /**
     * 购物车所属对象
     *
     * @var string
     */
    private $owner;


    /**
     * 行项目集合
     *
     * @var array
     */
    private $rows = [];

    /**
     * 购物车行项目数量
     *
     * @var int
     */
    private $count = 0;

    /**
     * 购物车初始化
     *
     * @param int $owner 购物车所属对象
     */
    public function __construct(int $owner)
    {
        //设置购物车所属对象
        $this->setOwner($owner);


        //获取购物车信息
        $cartRows = $this->getUserRows();

        //初始化购物车对象
        if (!empty($cartRows)) {
            foreach ($cartRows as $row) {
                $rowObject = new RowObject($row);
                $this->rows[$rowObject->id] = $rowObject;
            }
        }

        //统计行项目数量
        $this->count = count($this->rows);
    }


    /**
     * 设置购物车所属对象
     *
     * @param $owner
     */
    private function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * 获取购物车行项目列表
     *
     * @return array
     */
    private function getUserRows()
    {
        return CartDao::getUnlimitedItemList(['user_id' => $this->owner]);
    }

    /**
     * 根据 ID 获取对应的行项目
     *
     * @param $id
     * @return object
     */
    public function getRowById($id)
    {
        return $this->rows[$id];
    }

    /**
     *  统计行项目数
     *
     * @return mixed
     */
    public function countCart()
    {
        return $this->count;
    }

    /**
     * 判断购物车是否存在菜品
     *
     * @param RowObject $row
     * @return integer|null
     */
    public function isExistRow(RowObject $row)
    {
        //遍历校验行项目对象是否存在购物车里面
        foreach ($this->rows as $cartRow) {
            if ($cartRow->skuId == $row->skuId) {
                return $cartRow->id;
            }
        }
        return null;
    }

    /**
     * 返回购物车行项目对象
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }


    /**
     * 加入购物车
     *
     * @param RowObject $row
     * @return int
     */
    public function addDishToCart(RowObject $row)
    {
        if ($this->count >= Config::get('order.cart.max_row')) {
            throw new UnsupportedOperationException('已超过购物车最大容量！');
        }

        //如果不存在则新增到购物车
        $cartModel = CartModel::create([
            'user_id' => $this->owner,
            'shop_id' => $row->shopId,
            'dish_id' => $row->dishId,
            'sku_id' => $row->skuId,
            'quantity' => $row->getQuantity(),
            'platform' => $row->platform,
        ]);

        $this->rows[$cartModel->id] = $row;

        //新增行项目
        $this->count++;

        return $cartModel->id;
    }


    /***
     *  更新购物袋
     *
     * @param RowObject $row
     * @param $cartId
     *
     * @return bool
     */
    public function updateCartDish(RowObject $row, $cartId)
    {
        //获取购物车里的行对象
        $rowObject = $this->rows[$cartId];
        $rowObject->modifyNum($rowObject->getQuantity() + $row->getQuantity());

        return true;
    }

    /**
     * 修改购物车行项目数量
     *
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function modifyRowNum(int $id, int $quantity)
    {
        if (!isset($this->rows[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->rows[$id]->modifyQuantity($quantity);
    }


    /**
     *  修改购物车行项目的skuId
     *
     * @param int $id
     * @param int $skuId
     * @return mixed
     */
    public function modifyRowSku(int $id, int $skuId)
    {
        if (!isset($this->rows[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->rows[$id]->modifySku($skuId);
    }


    /**
     * 移除购物车行项目
     *
     * @param array $ids 行项目id集合
     * @return array
     */
    public function removeRowFromCart(array $ids)
    {
        $cartIds = array_keys($this->rows);

        //取交集
        $intersectIds = array_values(array_intersect($ids, $cartIds));


        //如果交集为空则直接返回
        if (!empty($intersectIds)) {
            CartModel::query()
                ->whereIn('id', $intersectIds)
                ->delete();

            $this->rows = array_except($this->rows, $intersectIds);

            //减掉对应行数
            $this->count = $this->count - count($intersectIds);
        }

        return ['status' => true, 'removed_items' => $intersectIds];
    }

    /**
     * 清空购物车
     *
     * @return bool
     */
    public function clearCart()
    {
        $cartIds = array_keys($this->rows);

        CartModel::query()
            ->whereIn('id', $cartIds)
            ->delete();

        $this->rows = [];

        $this->count = 0;

        return true;
    }


}