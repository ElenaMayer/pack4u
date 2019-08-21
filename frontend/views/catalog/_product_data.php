<?php

use yii\helpers\Html;
?>

<div class="col-md-6">
    <div class="product-slider-wrapper thumbs-bottom">
        <div class="swiper-container product-slider-main">
            <div class="swiper-wrapper">
                <?php if($diversityId):?>
                    <div class="swiper-slide">
                        <?= Html::img($diversity->image->getUrl(), ['width' => '100%', 'alt'=>$diversity->title]);?>
                    </div>
                <?php endif;?>
                <?php foreach ($product->images as $key => $image):?>
                    <div class="swiper-slide">
                        <?= Html::img($image->getUrl(), ['width' => '100%', 'alt'=>$product->title . ' ' . $product->size. 'см']);?>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="swiper-button-prev"><i class="fa fa-chevron-left"></i></div>
            <div class="swiper-button-next"><i class="fa fa-chevron-right"></i></div>
        </div><!-- /.swiper-container -->
        <div class="swiper-container product-slider-thumbs">
            <div class="swiper-wrapper">
                <?php if($diversityId):?>
                    <div class="swiper-slide">
                        <?= Html::img($diversity->image->getUrl('small'), ['width' => '100%', 'alt'=>$diversity->title]);?>
                    </div>
                <?php endif;?>
                <?php foreach ($product->images as $key => $image):?>
                    <div class="swiper-slide">
                        <?= Html::img($image->getUrl('small'), ['width' => '100%', 'alt'=>$product->title]);?>
                    </div>
                <?php endforeach;?>
            </div>
        </div><!-- /.swiper-container -->
    </div><!-- /.product-slider-wrapper -->
</div>
<div class="summary entry-summary">
    <h1 class="product_title entry-title">
        <?= Html::encode($product->title) ?>
    </h1>
    <div class="product-status">
        <?php if($diversityId):?>
            <?php if($diversity->count > 0):?><span class="green">В наличии </span><?php else:?><span class="red">Отсутствует</span><?php endif;?>
        <?php else:?>
            <?php if($product->getIsInStock()):?><span class="green">В наличии </span><?php else:?><span class="red">Отсутствует</span><?php endif;?>
        <?php endif;?>
        <span>&nbsp;-&nbsp;</span>
        <span>Арт: <?php if($diversityId):?><?=$diversity->article?><?php else:?><?= $product->article?><?php endif;?></span>
    </div>
    <?php if($product->getIsInStock($diversityId) && $product->multiprice): ?>
        <?php foreach ($product->prices as $price):?>
            <?php
            if($product->diversity){
                $count = 0;
                foreach ($product->diversities as $div){
                    if($div->count > $count){
                        $count = $div->count;
                    }
                }
            } else {
                $count = $product->count;
            }
            if($price->count <= $count):?>
                <p class="multiprice">
                    <span class="amount"><?= $price->price ?><i class="fa fa-ruble"></i></span>
                    <span class="count"> от <?= $price->count ?> шт.</span>
                </p>
            <?php endif;?>
        <?php endforeach;?>
    <?php elseif($product->getIsInStock($diversityId) && $product->new_price): ?>
        <p class="price">
            <span class="amount old"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
            <span class="amount new"><?= (int)$product->new_price ?><i class="fa fa-ruble"></i></span>
        </p>
    <?php else:?>
        <p class="price">
            <span class="amount"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
        </p>
    <?php endif;?>
    <?php if($product->diversity && $product->diversities):?>
        <div class="diversities">
            <?php foreach ($product->diversities as $div):?>
                <?php if($div->is_active):?>
                    <div class="diversity <?php if($diversityId && $div->id == $diversityId):?>active<?php endif;?><?php if($div->count <= 0):?> out_of_stock<?php endif;?>">
                        <div class="diversity_inner">
                            <?php if($div->count <= 0):?>
                                <?= Html::img($div->image->getUrl('small'), ['alt'=>$div->title]);?>
                            <?php else:?>
                                <a id="diversity-link" data-href="/catalog/<?= $product->category->slug?>/<?= $product->id?>/<?= $div->id?>?ajax=1" title="<?= $div->title?>">
                                    <?= Html::img($div->image->getUrl('small'), ['alt'=>$div->title]);?>
                                </a>
                            <?php endif;?>
                            <?php if($div->count <= 0):?><span class="sold-out valign">Отсутствует</span><?php endif;?>
                        </div>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
        <div class="diversity-error has-error" style="display: none">Выберите расцветку</div>
    <?php endif;?>
    <p class="description"><?= $product->description ?></p>
    <div class="product_meta">
        <?php if($product->instruction):?>
            <span class="posted_in instruction"><a href="/instruction/<?=$product->instruction?>" target="_blank">Смотреть инструкцию по сборке</a></span>
        <?php endif;?>
        <?php if($product->size):?>
            <span class="posted_in">Размер: <span class="value"><?= Html::encode($product->size) ?> см</span></span>
        <?php endif;?>
        <?php if($product->color):?>
            <span class="posted_in">Цвета:
                <?php foreach ($product->getColorsArray() as $key => $color):?>
                    <?= Html::a($color, ['/catalog', 'color' => $color])?>
                <?php endforeach;?>
                                    </span>
        <?php endif;?>
        <span class="posted_in">Категория: <a href="/catalog/<?= $category->slug?>" title="<?= $category->title?>"><?= $category->title?></a></span>
        <?php if($product->tags):?>
            <span class="tagged_as">Теги:
                <?php foreach ($product->getCurrentTagsArray() as $key => $tag):?>
                    <?= Html::a($tag, ['/catalog', 'tag' => $tag])?>
                <?php endforeach;?>
                                    </span>
        <?php endif;?>
    </div>
    <?php $quantity = $product->getQuantity(); ?>
    <?php if($product->getIsInStock($diversityId)):?>
        <form class="cart">
            <div class="quantity">
                <input type="number" step="1" min="1" name="quantity" value="1" title="Количество" class="input-text qty text product-qty" size="4"/>
                <input type="hidden" name="count" value="<?= $diversityId ? $diversity->count : $product->count ?>"/>
            </div>
            <?php if($product->diversity):?>
                <input type="hidden" id="diversity" value="1"/>
                <input type="hidden" id="diversity_id" value="<?= $diversityId ?>"/>
            <?php else:?>
                <input type="hidden" name="diversity" value="0"/>
            <?php endif;?>
            <div class="cd-customization">
                <button type="button" class="add-to-cart single_add_to_cart_button button" data-id="<?= $product->id ?>" data-diversity_id="<?= $diversityId ?>">
                    <em>В корзину</em>
                    <svg x="0px" y="0px" width="32px" height="32px" viewBox="0 0 32 32">
                        <path stroke-dasharray="19.79 19.79" stroke-dashoffset="19.79" fill="none" stroke="#FFFFFF" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" d="M9,17l3.9,3.9c0.1,0.1,0.2,0.1,0.3,0L23,11"/>
                    </svg>
                </button>
            </div>
        </form>
        <div class="yith-wcwl-add-to-wishlist <?php if($product->isInWishlist()):?>active<?php endif;?>">
            <div class="yith-wcwl-add-button">
                <a class='product_wishlist' id="<?=$product->id?>"></a>
            </div>
        </div>
        <?php if(Yii::$app->user->isGuest):?>
            <div class="wishlist-login hide">Для использования "Избранного" необходимо <a href="/user/login">Войти</a></div>
        <?php endif;?>
        <div class="count-error has-error" style="display: none">В наличии осталось <?= $diversityId ? $diversity->count : $product->count ?> шт.</div>
    <?php endif;?>
    <div class="clear"></div>
</div>