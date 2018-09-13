<?php
use \yii\helpers\Html; ?>

<li>
    <div class="cart-item <?php if(!$product->getIsInStock() || !$product->is_active):?>absent<?php endif;?>">
        <div class="product-image">
            <a href="/catalog/<?= $product->category->slug?>/<?= $product->id?>" title="<?= Html::encode($product->title)?>">
                <?= Html::img($product->images[0]->getUrl('small'), ['width' => '100%', 'alt'=>$product->title]);?>
            </a>
        </div>
        <div class="product-body">
            <div class="product-name">
                <h3>
                    <a href="/catalog/<?= $product->category->slug?>/<?= $product->id?>" title="<?= $product->title?>"><?= Html::encode($product->title)?></a>
                </h3>
            </div>
            <div class="product-count">
                <?php $quantity = $product->getQuantity()?>
                <span><?= $quantity?>шт.</span>
            </div>
            <div class="product-price">
                <span><?= (int)$product->getCost()?><i class="fa fa-ruble"></i></span>
            </div>
            <div class="count-error has-error" <?php if($quantity <= $product->count):?>style="display: none" <?php endif;?>>В наличии осталось <?=$product->count ?> шт.</div>
        </div>
    </div><!-- /.cart-item -->
    <?= Html::a('×', ['cart/remove', 'id' => $product->getId(), 'returnUrl' => '/cart/order'], ['class' => 'remove'])?>
</li>