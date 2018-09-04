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
                    echo Html::img($images[0]->getUrl('medium'), ['width' => '600', 'height' => '760', 'alt' => $model->title . ' ' . $model->size. 'см']);
                }
                ?>
            </a>
        </div>
        <?php if($model->getIsInStock()): ?>
            <?php if($model->new_price): ?>
                <span class="product-label sale">
                    <span>-<?=$model->getSale()?>%</span>
                </span>
            <?php elseif($model->is_novelty): ?>
                <span class="product-label new">
                    <span>new</span>
                </span>
            <?php endif;?>
        <?php endif;?>
        <div class="noo-product-title">
            <h3><a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>" title="<?= $model->title?>"><?= Html::encode($model->title) ?></a></h3>
            <?php if($model->getIsInStock() && $model->new_price): ?>
                <span class="price">
                    <span class="amount old"><?= (int)$model->price ?><i class="fa fa-ruble"></i></span>
                    <span class="amount new"><?= (int)$model->new_price ?><i class="fa fa-ruble"></i></span>
                </span>
            <?php else:?>
                <span class="price"><span class="amount"><?= (int)$model->price ?><i class="fa fa-ruble"></i></span></span>
            <?php endif;?>

            <?php if($model->size): ?>
                <div class="noo-product-size">
                    <p><?= $model->size?> см</p>
                </div>
            <?php endif;?>
            <div class="noo-product-excerpt">
                <p><?= $model->description?></p>
            </div>
            <?php if($model->getIsInStock()):?>
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