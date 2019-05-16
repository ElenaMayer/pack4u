<?php
use \yii\helpers\Html;
use common\models\ProductDiversity;

$diversity = ProductDiversity::findOne($position->diversity_id);
?>

<li>
    <div class="cart-item <?php if(!$product->getIsInStock($diversity->id)):?>absent<?php endif;?>">
        <div class="product-image">
            <?php if($diversity):?>
                <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>/<?=$diversity->id?>" title="<?= Html::encode($product->title)?>">
                    <?= Html::img($diversity->image->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$diversity->title]);?>
                </a>
            <?php else:?>
                <a href="/catalog/<?= $product->category->slug ?>/<?= $product->id ?>" title="<?= Html::encode($product->title)?>">
                    <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$product->title]);?>
                </a>
            <?php endif;?>
        </div>
        <div class="product-body">
            <div class="product-name">
                <h3>
                    <?php if($diversity):?>
                        <a href="/catalog/<?= $product->category->slug?>/<?= $product->id?>/<?= $diversity->id?>" title="<?= $diversity->title?>"><?= Html::encode($product->title)?> "<?= $diversity->title?>"</a>
                    <?php else:?>
                        <a href="/catalog/<?= $product->category->slug?>/<?= $product->id?>" title="<?= $product->title?>"><?= Html::encode($product->title)?></a>
                    <?php endif;?>
                </h3>
            </div>
            <div class="product-count">
                <?php $quantity = $position->getQuantity()?>
                <?php $count = ($diversity) ? $diversity->count : $product->count; ?>
                <span><?= $quantity?>шт.</span>
            </div>
            <div class="product-price">
                <span><?= (int)$position->getCost()?><i class="fa fa-ruble"></i></span>
            </div>
            <div class="count-error has-error" <?php if($quantity <= $count):?>style="display: none" <?php endif;?>>В наличии осталось <?=$count ?> шт.</div>
        </div>
    </div><!-- /.cart-item -->
    <td class="product-remove"><a data-id="<?= $position->getId() ?>" id="remove_order_item" class="remove">×</a></td>
</li>