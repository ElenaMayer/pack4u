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
                        <th class="product-thumbnail">Товар</th>
                        <th class="product-price">Цена</th>
                        <th class="product-quantity">Количество</th>
                        <th class="product-subtotal">Всего</th>
                        <th class="product-remove">&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product):?>
                        <?php
                        $quantity = $product->getQuantity(); ?>
                        <tr class="cart_item">
                        <td class="product-thumbnail">
                            <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>">
                                <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$product->title]);?>
                            </a>
                            <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>"><?= Html::encode($product->title) ?></a>
                        </td>
                        <td class="product-price">
                            <span class="amount"><?= (int)$product->price ?>₽</span>
                        </td>
                        <td class="product-quantity">
                            <div class="quantity">
                                <?= Html::a('-', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity - 1], ['class' => 'btn btn-danger', 'disabled' => ($quantity - 1) < 1])?>
                                <input type="number" step="1" min="0" name="qty" value="<?= $quantity ?>" class="input-text qty text" size="4"/>
                                <?= Html::a('+', ['cart/update', 'id' => $product->getId(), 'quantity' => $quantity + 1], ['class' => 'btn btn-success'])?>
                            </div>
                        </td>
                        <td class="product-subtotal">
                            <span class="amount"><?= $product->getCost() ?>₽</span>
                        </td>
                        <td class="product-remove">
                            <?= Html::a('×', ['cart/remove', 'id' => $product->getId()], ['class' => 'remove'])?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="cart-collaterals">
                    <a class="continue" href="/catalog">Продолжить покупки</a>
                    <div class="cart_totals">
                        <?= $this->render('_total', ['total'=>$total]); ?>
                        <div class="wc-proceed-to-checkout">
                            <?= Html::a('Оформить заказ', ['cart/order'], ['class' => 'checkout-button button alt wc-forward'])?>
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
            <h4>- Every day fresh -</h4>
            <h3>organic food</h3>
        </div>
        <img src="images/organici-love-me.png" class="noo-image-footer" alt="" />
    </div>
</div>
