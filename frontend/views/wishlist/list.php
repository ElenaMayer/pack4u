<?php
use \yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Избранное';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(count($wishlist) > 0):?>
<div class="commerce commerce-wishlist noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-12">
                <table class="shop_table cart wishlist_table">
                    <thead>
                    <tr>
                        <th class="product-remove"></th>
                        <th class="product-thumbnail"></th>
                        <th class="product-name">
                            <span class="nobr">Название</span>
                        </th>
                        <th class="product-stock-stauts">
                            <span class="nobr">Статус </span>
                        </th>
                        <th class="product-price">
                            <span class="nobr">Цена </span>
                        </th>
                        <th class="product-add-to-cart"></th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($wishlist as $wishlistItem):?>
                            <?php $product = $wishlistItem->product; ?>
                            <?php if($product->getIsActive()):?>
                                <tr>
                                    <td class="product-remove">
                                        <div>
                                            <?= Html::a('×', ['/wishlist/remove', 'id' => $wishlistItem->id], ['class' => 'remove'])?>
                                        </div>
                                    </td>
                                    <td class="product-thumbnail">
                                        <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>">
                                            <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$product->title]);?>
                                        </a>
                                    </td>
                                    <td class="product-name">
                                        <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>"><?= Html::encode($product->title) ?></a>
                                        <p><?= $product->size ? Html::encode($product->size). ' см': '' ?></p>
                                    </td>
                                    <td class="product-stock-status">
                                        <span class="wishlist-in-stock <?= $product->getIsInStock() ? 'green' : 'red' ?>"><?= $product->getIsInStock() ? 'В наличии' : 'Отсутствует' ?></span>
                                    </td>
                                    <td class="product-price">
                                        <span class="amount"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
                                    </td>
                                    <td class="product-add-to-cart">
                                        <?php if($product->getIsInStock()):?>
                                            <?= Html::a('<span>В корзину</span>', ['/cart/add', 'id' => $product->id], ['class' => 'button add_to_cart button'])?>
                                        <?php endif;?>
                                    </td>
                                </tr>
                            <?php endif;?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                        В избранном в данный момент нет товаров.
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
            <h4>- Выбирайте нас -</h4>
            <h3>Лучшая упаковка тут</h3>
        </div>
    </div>
</div>