<?php

namespace App\Services\Order;

use App\Contracts\Order\CartInterface;
use App\Object\Cart\CartObject;
use App\Object\Cart\RowObject;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Liviator\Exception\IllegalArgumentException;
use Liviator\Exception\LiviatorException;
use Liviator\Exception\OperationFailedException;
use Liviator\Exception\ResourceNotFoundException;

/***
 * 购物车相关功能
 *
 * @author Qianc
 */
class CartManager implements CartInterface
{

    /**
     * 加入购物车
     *
     * @param int $owner 所有者
     * @param array $cartDish 要加入的菜品
     * @return array
     * @throws ValidationException
     */
    public function addDishToCart(int $owner, array $cartDish)
    {
        try {
            //组装信息
            $skuId = $cartDish['sku_id'];

            //todo 获取商品信息
            $skuInfo = [];
            //是否存在商品信息
            if (!$skuInfo) {
                throw new ResourceNotFoundException('商品不存在！');
            }

            if ($skuInfo['item']['id'] != $cartDish['dish_id']) {
                throw new IllegalArgumentException('商品规格不匹配！');
            }


            //todo 验证商品当前上下架状态
            if ('商品未上架') {
                throw new IllegalArgumentException('商品未上架');
            }

            //todo 验证商品库存
            $currentStore = 0;
            if ($currentStore[$skuId]['total'] <= 0) {
                throw new IllegalArgumentException('商品库存不足');
            }

            //校验商品有效性
            $cartObject = new CartObject($owner);
            $dish = new RowObject($cartDish);

            //判断是否存在购物车里
            $cartIdExist = $cartObject->isExistRow($dish);
            if ($cartIdExist) {
                $cartObject->updateCartDish($cartDish, $cartIdExist);

                return ['count' => $cartObject->countCart(), 'cart_id' => $cartIdExist];
            } else {
                $cartId = $cartObject->addDishToCart($dish);
                $count = $cartObject->countCart();

                // todo 加购成功发送广播


                return ['count' => $count, 'cart_id' => $cartId];
            }

        } catch (LiviatorException|ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::channel('cart')->error("加入购物车失败", [
                'params' => ['owner' => $owner, 'cart_dish' => $cartDish],
                'exception' => $e
            ]);
            throw new OperationFailedException('加入购物车失败！');
        }

    }
}