<?php

namespace frontend\models;

use yz\shoppingcart\CostCalculationEvent;
use yz\shoppingcart\ShoppingCart;

class MyShoppingCart extends ShoppingCart
{

    public function behaviors()
    {
        return [
            'myDiscount' => 'frontend\components\MyDiscount',
        ];
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return count($this->_positions);
    }

    public function getDiscount()
    {
        return 0; // without discount

        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += $position->getCost();
        }
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        $this->trigger(self::EVENT_COST_CALCULATION, $costEvent);
        return $costEvent->discountValue;
    }

    public function getDiscountPercent()
    {
        return 0; // without discount

        $cost = 0;
        foreach ($this->_positions as $position) {
            $cost += $position->getCost();
        }
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        $this->trigger(self::EVENT_COST_CALCULATION, $costEvent);

        return $costEvent->discountValue * 100 / $cost;
    }

    public function getCost($withDiscount = false)
    {
        return parent::getCost(false); // without discount
    }
}