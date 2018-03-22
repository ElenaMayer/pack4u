<?php

namespace frontend\models;


use yz\shoppingcart\ShoppingCart;

class MyShoppingCart extends ShoppingCart
{
    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->_positions);
    }
}