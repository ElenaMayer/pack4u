<?php

namespace frontend\components;

use yz\shoppingcart\DiscountBehavior;

class MyDiscount extends DiscountBehavior
{

    private function getDiscountValues(){

        return [
            ['cost' => 15000, 'value' => 7],
            ['cost' => 10000, 'value' => 5],
            ['cost' => 5000, 'value' => 3],
        ];
    }

    /**
     * @param CostCalculationEvent $event
     */
    public function onCostCalculation($event)
    {
        $discountValue = 0;
        foreach ($this->getDiscountValues() as $discount){
            if($event->baseCost >= $discount['cost']){
                $discountValue = $event->baseCost * $discount['value'] / 100;
                break;
            }
        }
        $event->discountValue = $discountValue;
    }
}