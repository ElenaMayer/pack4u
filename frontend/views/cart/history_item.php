<?php
use \yii\helpers\Html;

$this->title = 'Заказ #' . $order->id;

$this->params['breadcrumbs'][] = ['label' => 'История заказов', 'url' => ['/history']];
$this->params['breadcrumbs'][] = $this->title;

?>
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
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($order->orderItems as $item):?>
                        <?php
                        $product = $item->product; ?>
                        <tr class="cart_item">
                            <td class="product-img">
                                <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>">
                                    <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$product->title]);?>
                                </a>
                            </td>
                            <td class="product-thumbnail">
                                <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>"><?= Html::encode($product->title) ?></a>
                            </td>
                            <td class="product-price">
                                <span class="amount"><?= (int)$item->price ?><i class="fa fa-ruble"></i></span>
                            </td>
                            <td class="product-quantity">
                                <?= $item->quantity ?>
                            </td>
                            <td class="product-subtotal">
                                <span class="amount"><?= $item->getCost() ?><i class="fa fa-ruble"></i></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="cart-collaterals">
                    <div class="cart_totals">
                        <div id="data_total">
                            <?= $this->render('_total', [
                                    'order' => $order,
                                    'subtotal' => $order->getSubCost(),
                                    'total' => $order->getCost(),
                                    'discount' => $order->getDiscountValue(),
                                    'discountPercent' => $order->discount,
                                ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Красивая упаковка -</h4>
            <h3>Спасибо что выбрали нас</h3>
        </div>
    </div>
</div>
