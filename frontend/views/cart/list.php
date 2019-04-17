<?php
use \yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(count($products) > 0):?>
<div class="commerce commerce-page commerce-cart noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-12">
                <table class="shop_table cart">
                    <thead>
                    <tr>
                        <th class="product-img">Товар</th>
                        <th class="product-thumbnail">Название</th>
                        <th class="product-price">Цена</th>
                        <th class="product-quantity">Количество</th>
                        <th class="product-subtotal">Всего</th>
                        <th class="product-remove">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product):?>
                        <?php if($product->getIsActive()):?>
                            <?php $quantity = $product->getQuantity(); ?>
                            <tr class="cart_item <?php if(!$product->getIsInStock()):?>out_of_stock<?php endif;?>">
                                <td class="product-img">
                                    <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>">
                                        <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$product->title]);?>
                                    </a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>"><?= Html::encode($product->title) ?>
                                        <div class="product_size"><?= $product->size ?> см</div>
                                        <?php if(!$product->getIsInStock()):?><span class="out_of_stock_title">Нет в наличии</span><?php endif;?>
                                    </a>
                                </td>
                                <td class="product-price">
                                    <?php if($product->multiprice): ?>
                                        <div class="price">
                                            <span class="amount">
                                                <span id="amount_price_<?= $product->id ?>">
                                                    <?= (int)$product->getMultiprice($quantity) ?>
                                                </span>
                                                <i class="fa fa-ruble"></i>
                                                &nbsp;
                                                <div class="price_tooltip">
                                                    <i class="fa fa-question-circle"></i>
                                                    <span class="tooltip-text">
                                                        <?php foreach ($product->prices as $price):?>
                                                            <?php if($price->count <= $product->count):?>
                                                                <p>от <?= $price->count?>шт. - <?= $price->price?><i class="fa fa-ruble"></i></p>
                                                            <?php endif;?>
                                                        <?php endforeach;?>
                                                    </span>
                                                </div>
                                            </span>
                                        </div>
                                    <?php elseif($product->getIsInStock() && $product->getNewPrice()): ?>
                                        <p class="price">
                                            <span class="amount old"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
                                            <span class="amount new"><?= (int)$product->getNewPrice() ?><i class="fa fa-ruble"></i></span>
                                        </p>
                                    <?php else:?>
                                        <p class="price"><span class="amount"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span></p>
                                    <?php endif;?>
                                </td>
                                <td class="product-quantity">
                                    <?php if($product->getIsInStock()):?>
                                        <form id="update-qty-<?=$product->getId()?>" method="get">
                                            <div class="quantity">
                                                <input type="number" step="1" min="1" name="quantity" value="<?= $quantity ?>" class="input-text cart-qty qty text" size="4"/>
                                                <input type="hidden" name="id" value="<?=$product->getId()?>">
                                                <input type="hidden" name="count" value="<?= $product->count ?>"/>
                                            </div>
                                        </form>
                                        <div class="count-error has-error" <?php if($quantity <= $product->count):?>style="display: none" <?php endif;?>>В наличии осталось <?=$product->count ?> шт.</div>
                                    <?php else:?>
                                        0
                                    <?php endif;?>
                                </td>
                                <td class="product-subtotal">
                                    <span class="amount"><span id="amount_val_<?= $product->id ?>"><?= $product->getCost() ?></span><i class="fa fa-ruble"></i></span>
                                </td>
                                <td class="product-remove"><a data-id="<?= $product->id ?>" id="remove_cart_item" class="remove">×</a></td>
                            </tr>
                        <?php endif;?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="cart-collaterals">
                    <a class="continue" href="/catalog">Продолжить покупки</a>
                    <div class="cart_totals">
                        <div id="data_total">
                            <?= $this->render('_total', [
                                'subtotal' => $cart->getCost(),
                                'total' => $cart->getCost(true),
                                'discount' => $cart->getDiscount(),
                                'discountPercent' => $cart->getDiscountPercent(),
                            ]); ?>
                        </div>
                        <div class="wc-proceed-to-checkout">
                            <?= Html::a('Оформить заказ',
                                ['cart/order'],
                                ['class' => 'checkout-button button alt wc-forward' . (($cart->getCost() < Yii::$app->params['orderMinSum'])? ' disabled': '')])?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="commerce commerce-cart noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-12">
                <p class="cart-empty">
                    Ваша корзина в данный момент пуста.
                </p>
                <p class="return-to-shop">
                    <a class="button wc-backward" href="/catalog">
                        Вернуться к покупкам
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php endif;?>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Красивая упаковка -</h4>
            <h3>ДЛЯ ВАС</h3>
        </div>
    </div>
</div>
