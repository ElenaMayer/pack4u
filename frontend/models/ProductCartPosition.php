<?php

namespace frontend\models;

use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CostCalculationEvent;
use common\models\Product;


class ProductCartPosition implements CartPositionInterface
{
    /**
     * @var Product
     */
    protected $_product;
    protected $_quantity;

    public $id;
    public $price;
    public $diversity_id;

    public function getId()
    {
        return md5(serialize([$this->id, $this->diversity_id]));
    }

    public function getPrice($qty = 0)
    {
        $product = $this->getProduct();
        if(!$product->getIsInStock($this->diversity_id) || !$product->getIsActive($this->diversity_id))
            return 0;
        else
            return $product->getPrice($qty, $this->diversity_id);
    }

    public function getDiversity()
    {
        return $this->getProduct()->diversity_id;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if ($this->_product === null) {
            $this->_product = Product::findOne($this->id);
        }
        return $this->_product;
    }

    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function getActiveQuantity()
    {
        $product = $this->getProduct();
        if($product->getIsInStock()){
            return $this->_quantity;
        } else {
            return 0;
        }
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    /**
     * Default implementation for getCost function. Cost is calculated as price * quantity
     * @param bool $withDiscount
     * @return int
     */
    public function getCost($withDiscount = true)
    {
        /** @var Component|CartPositionInterface|self $this */
        $cost = $this->getQuantity() * $this->getPrice($this->getQuantity());
        $costEvent = new CostCalculationEvent([
            'baseCost' => $cost,
        ]);
        if ($this instanceof Component)
            $this->trigger(CartPositionInterface::EVENT_COST_CALCULATION, $costEvent);
        if ($withDiscount)
            $cost = max(0, $cost - $costEvent->discountValue);
        return $cost;
    }
}