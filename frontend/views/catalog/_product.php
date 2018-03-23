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
                    echo Html::img($images[0]->getUrl(), ['width' => '600', 'height' => '760', 'alt' => $model->title]);
                }
                ?>
            </a>
        </div>
        <div class="noo-product-title">
            <h3><a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>" title="<?= $model->title?>"><?= Html::encode($model->title) ?></a></h3>
            <span class="price"><span class="amount"><?= (int)$model->price ?>₽</span></span>
            <div class="noo-product-action">
                <div class="noo-action">
                    <?= Html::a('<span>В корзину</span>', ['/cart/add', 'id' => $model->id], ['class' => 'button product_type_simple add_to_cart_button'])?>
                </div>
            </div>
        </div>
    </div>
</div>