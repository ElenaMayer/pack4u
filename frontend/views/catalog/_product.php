<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<?php /** @var $model \common\models\Product */ ?>

<div class="masonry-item noo-product-column col-md-4 col-sm-6 product">
    <div class="noo-product-inner">
        <div class="noo-product-thumbnail">
            <a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>" title="<?= $model->title?>">
                <?php
                $images = $model->images;
                if (isset($images[0])) {
                    echo Html::img($images[0]->getUrl('medium'), ['width' => '600', 'height' => '760', 'alt' => $model->title]);
                }
                ?>
            </a>
        </div>
        <div class="noo-product-title">
            <h3><a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>" title="<?= $model->title?>"><?= Html::encode($model->title) ?></a></h3>
            <span class="price"><span class="amount"><?= (int)$model->price ?>&#8381</span></span>
            <div class="noo-product-size">
                <p><?= $model->size?></p>
            </div>
            <div class="noo-product-excerpt">
                <p><?= $model->description?></p>
            </div>
            <?php if($model->is_in_stock):?>
                <div class="noo-product-action">
                    <div class="noo-action">
                        <?= Html::a('<span>В корзину</span>', ['/cart/add', 'id' => $model->id], ['class' => 'button product_type_simple add_to_cart_button'])?>
                        <div class="yith-wcwl-add-to-wishlist">
                            <div class="yith-wcwl-add-button">
                                <a href="#" class="add_to_wishlist"></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else:?>
                <div class="noo-product-action out-of-stock">
                    <div class="noo-action">
                        Нет в наличии
                    </div>
                </div>
            <?php endif;?>
        </div>
    </div>
</div>