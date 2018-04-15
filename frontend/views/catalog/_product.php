<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
?>
<?php /** @var $model \common\models\Product */ ?>

<div class="masonry-item noo-product-column <?php if(isset($type) && $type == 'small'):?>col-md-3 col-sm-5<?php else:?>col-md-4 col-sm-6<?php endif;?> product">
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
            <span class="price"><span class="amount"><?= (int)$model->price ?><i class="fa fa-ruble"></i></span></span>
            <?php if($model->size): ?>
                <div class="noo-product-size">
                    <p><?= $model->size?> см</p>
                </div>
            <?php endif;?>
            <div class="noo-product-excerpt">
                <p><?= $model->description?></p>
            </div>
            <?php if($model->is_in_stock):?>
                <div class="noo-product-action">
                    <div class="noo-action">
                        <?= Html::a('<span>В корзину</span>', ['/cart/add', 'id' => $model->id], ['class' => 'button product_type_simple add_to_cart_button'])?>
                        <?php if(!Yii::$app->user->isGuest):?>
                            <div class="yith-wcwl-add-to-wishlist <?php if($model->isInWishlist()):?>active<?php endif;?>">
                                <div class="yith-wcwl-add-button">
                                    <a class='catalog_wishlist' id="<?=$model->id?>"></a>
                                </div>
                            </div>
                        <?php endif;?>
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