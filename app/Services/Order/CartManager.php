<?php
namespace App\Services\Order;

use App\Contracts\Order\CartInterface;

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
     * @return bool
     */
    public function addDishToCart(int $owner, array $cartDish)
    {
        // TODO: Implement addDishToCart() method.
    }
}