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
                                            <a data-id="<?= $wishlistItem->id ?>" id="remove_wl_item" class="remove">×</a>
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
                                        <?php if($product->getIsInStock() && $product->getNewPrice()): ?>
                                            <p class="price">
                                                <span class="amount old"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
                                                <span class="amount new"><?= (int)$product->getNewPrice() ?><i class="fa fa-ruble"></i></span>
                                            </p>
                                        <?php else:?>
                                            <p class="price"><span class="amount"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span></p>
                                        <?php endif;?>
                                    </td>
                                    <td class="product-add-to-cart">
                                        <?php if($product->getIsInStock()):?>
                                            <div class="cd-customization">
                                                <button type="button" class="add-to-cart single_add_to_cart_button button" data-id="<?= $product->id ?>">
                                                    <em>В корзину</em>
                                                    <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                                                        <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/>
                                                    </svg>
                                                </button>
                                            </div>
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