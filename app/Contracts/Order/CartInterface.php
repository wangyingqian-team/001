<?php
namespace App\Contracts\Order;

/***
 * 购物车接口
 *
 * @author Qianc
 */
interface CartInterface
{
    /**
     * 加入购物车
     *
     * @param int $owner  所有者
     * @param array $cartDish 要加入的菜品
     * @return bool
     */
    public function addDishToCart(int $owner, array $cartDish);
}