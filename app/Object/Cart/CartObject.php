<?php
namespace App\Object\Cart;

use App\Models\Order\CartModel;

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
        $cartItemInfo = $this->getCartItemInfo();

        //初始化购物车对象
        if ($cartItemInfo) {
            foreach ($cartItemInfo as $item) {
                $rowObject = new RowObject($item);
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
     * 获取购物车信息
     *
     * @return array
     */
    private function getCartRowInfo()
    {
        //拉取购物车信息
        return CartModel::query()
            ->where('user_id', $this->owner)
            ->get()->toArray();
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
     * 判断购物车是否存在商品
     *
     * @param \TrOrder\Cart\Object\ItemObject
     * @return integer|null
     */
    public function isExistItem(ItemObject $item)
    {
        //遍历校验行项目对象是否存在购物车里面
        foreach ($this->items as $cartItem) {
            if ($cartItem->skuId == $item->skuId) {
                return $cartItem->id;
            }
        }
        return null;
    }

    /**
     * 返回购物车行项目对象
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * 加入购物车
     *
     * @param \TrOrder\Cart\Object\ItemObject $item
     * @return int
     * @throws ValidationException
     */
    public function addItemToCart(ItemObject $item)
    {
        try {

            if($this->count >= Config::get('order.cart.max_line')) throw new UnsupportedOperationException('已超过购物车最大容量！');

            //如果不存在则新增到购物车
            $cartModel = CartModel::create([
                'user_id' => $this->owner,
                'user_ident' => '',
                'channel' => $this->channel,
                'shop_id' => $item->shopId,
                'item_id' => $item->itemId,
                'sku_id' => $item->skuId,
                'quantity' => $item->getNum(),
                'platform' => $item->platform,
                'extra' => $item->extra,
            ]);

            Log::channel('cart')->info("加入购物车成功", ['ItemObject' => $item]);

            $this->items[$cartModel->id] = $item;

            //新增行项目
            $this->count++;

        } catch (LiviatorException|ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::channel('cart')->error("加入购物车失败", [
                'params' => ['ItemObject' => $item, 'user_id' => $this->owner],
                'exception' => $e
            ]);
            throw new OperationFailedException('加入购物车失败！');
        }

        return $cartModel->id;
    }


    /***
     *  更新购物袋
     *
     * @param ItemObject $item
     * @param $cartId
     *
     * @return bool
     */
    public function updateCartItem(ItemObject $item, $cartId)
    {
        //获取购物车里的行对象
        $itemObject = $this->items[$cartId];
        $itemObject->modifyNum($itemObject->getNum() + $item->getNum());

        //replace extra中部分属性 eg: commission_user_id
        if(!empty($item->extra['commission_user_id']))
            $itemObject->modifyExtraField('commission_user_id', $item->extra['commission_user_id']);
        if(!empty($item->extra['name_card_salesman_id']))
            $itemObject->modifyExtraField('name_card_salesman_id', $item->extra['name_card_salesman_id']);
        if(!empty($item->extra['commission_ucenter_id']))
            $itemObject->modifyExtraField('commission_ucenter_id', $item->extra['commission_ucenter_id']);

        return true;
    }

    /**
     * 修改购物车行项目数量
     *
     * @param int $id
     * @param int $num
     * @return bool
     */
    public function modifyItemNum(int $id, int $num)
    {
        if (!isset($this->items[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->items[$id]->modifyNum($num);
    }


    /**
     *  修改购物车行项目的skuId
     *
     * @param int $id
     * @param int $skuId
     * @return mixed
     */
    public function modifyItemSku(int $id, int $skuId)
    {
        if (!isset($this->items[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->items[$id]->modifySku($skuId);
    }


    /**
     *  修改购物车行项目的price
     *
     * @param int $id
     * @param $price
     * @return mixed
     */
    public function modifyItemPrice(int $id, $price)
    {
        if (!isset($this->items[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->items[$id]->modifyExtraField('price', $price);
    }


    /**
     *  删除购物车行项目的price
     *
     * @param int $id
     * @return mixed
     */
    public function removeItemPrice(int $id)
    {
        if (!isset($this->items[$id])) {
            throw new ResourceNotFoundException("找不到行项目记录");
        }

        //调用行项目对象里的数量修改方法
        return $this->items[$id]->removeExtraField('price');
    }

    /**
     * 移除购物车行项目
     *
     * @param array $ids 行项目id集合
     * @return array | throw Exception
     * @throws ValidationException
     */
    public function removeItemFromCart(array $ids)
    {
        $cartIds = array_keys($this->items);

        //取交集
        $intersectIds = array_values(array_intersect($ids, $cartIds));

        try {
            //如果交集为空则直接返回
            if (!empty($intersectIds)) {
                CartModel::query()
                    ->whereIn('id', $intersectIds)
                    ->delete();

                $this->items = array_except($this->items, $intersectIds);

                //减掉对应行数
                $this->count = $this->count - count($intersectIds);
            }

            Log::channel('cart')->info("移除购物车行项目成功", ['ids' => $intersectIds]);

        } catch (LiviatorException|ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::channel('cart')->error("移除购物车行项目失败", [
                'params' => ['ids' => $ids, 'user_id' => $this->owner],
                'exception' => $e
            ]);
            throw new OperationFailedException('移除购物车行项目失败！');
        }

        return ['status' => true, 'removed_items' => $intersectIds];
    }

    /**
     * 清空购物车
     *
     * @return bool
     * @throws ValidationException
     */
    public function clearCart()
    {
        try {
            $cartIds = array_keys($this->items);

            CartModel::query()
                ->whereIn('id', $cartIds)
                ->delete();

            $this->items = [];

            $this->count = 0;

            Log::channel('cart')->info("清空购物车成功", ['user_id' => $this->owner]);
        } catch (LiviatorException|ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {
            Log::channel('cart')->error("清空购物车失败", [
                'params' => [ 'user_id' => $this->owner],
                'exception' => $e
            ]);
            throw new OperationFailedException('清空购物车失败！');
        }


        return true;
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
}