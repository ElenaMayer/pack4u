<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use \common\models\Product;
use frontend\assets\ProductAsset;

ProductAsset::register($this);

/* @var $this yii\web\View */
$title = $product->title;
$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['/catalog']];
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['/catalog/' . $category->slug]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="commerce single-product noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-9">
                <div class="product">
                    <div class="single-inner">
                        <?php $images = $product->images; ?>
                        <div class="col-md-6">
                            <div class="product-slider-wrapper thumbs-bottom">
                                <div class="swiper-container product-slider-main">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($images as $key => $image):?>
                                            <div class="swiper-slide">
                                                <?= Html::img($image->getUrl(), ['width' => '100%', 'alt'=>$product->title]);?>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                    <div class="swiper-button-prev"><i class="fa fa-chevron-left"></i></div>
                                    <div class="swiper-button-next"><i class="fa fa-chevron-right"></i></div>
                                </div><!-- /.swiper-container -->
                                <div class="swiper-container product-slider-thumbs">
                                    <div class="swiper-wrapper">
                                        <?php foreach ($images as $key => $image):?>
                                            <div class="swiper-slide">
                                                <?= Html::img($image->getUrl('small'), ['width' => '100%', 'alt'=>$product->title]);?>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                </div><!-- /.swiper-container -->
                            </div><!-- /.product-slider-wrapper -->
                        </div>
                        <div class="summary entry-summary">
                            <h1 class="product_title entry-title"><?= Html::encode($product->title) ?></h1>
                            <div class="product-status">
                                <?php if($product->getIsInStock()):?><span class="green">В наличии </span><?php else:?><span class="red">Отсутствует</span><?php endif;?>
                                <span>&nbsp;-&nbsp;</span>
                                <span>Арт: <?= $product->article?></span>
                            </div>
                            <?php if($product->getIsInStock() && $product->new_price): ?>
                                <p class="price">
                                    <span class="amount old"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span>
                                    <span class="amount new"><?= (int)$product->new_price ?><i class="fa fa-ruble"></i></span>
                                </p>
                            <?php else:?>
                                <p class="price"><span class="amount"><?= (int)$product->price ?><i class="fa fa-ruble"></i></span></p>
                            <?php endif;?>
                            <p class="description"><?= $product->description ?></p>
                            <div class="product_meta">
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
                            <?php if($product->getIsInStock()):?>
                                <form class="cart" action="/cart/add" method="get">
                                    <div class="quantity">
                                        <input type="number" step="1" min="1" name="quantity" value="1" title="Количество" class="input-text qty text product-qty" size="4"/>
                                        <input type="hidden" name="id" value="<?= $product->id ?>"/>
                                        <input type="hidden" name="count" value="<?= $product->count ?>"/>
                                    </div>
                                    <?= Html::button('В корзину', ['type' => 'submit', 'class' => 'single_add_to_cart_button button'])?>
                                </form>
                                <div class="yith-wcwl-add-to-wishlist <?php if($product->isInWishlist()):?>active<?php endif;?>">
                                    <div class="yith-wcwl-add-button">
                                        <a class='product_wishlist' id="<?=$product->id?>"></a>
                                    </div>
                                </div>
                                <?php if(Yii::$app->user->isGuest):?>
                                    <div class="wishlist-login hide">Для использования "Избранного" необходимо <a href="/user/login">Войти</a></div>
                                <?php endif;?>
                                <div class="count-error has-error" style="display: none">В наличии осталось <?=$product->count ?> шт.</div>
                            <?php endif;?>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <?php if ($relations = $product->getActiveRelations()):?>
                        <div class="related products">
                            <h2>С этим товаром также покупают:</h2>
                            <div class="products row product-grid">
                                <?php foreach (array_values($relations) as $index => $model) :?>
                                    <?= $this->render('_product', ['model' => $model->child, 'type' => 'small']); ?>
                                <?php endforeach;?>
                            </div>
                        </div>
                    <?php endif;?>
                </div>
            </div>
            <div class="noo-sidebar col-md-3">
                <div class="noo-sidebar-wrap">
                    <div class="widget commerce widget_product_search">
                        <h3 class="widget-title">Поиск</h3>
                        <form action="/search">
                            <input type="search" class="search-field" placeholder="Найти товар&hellip;" value="" name="s"/>
                            <input type="submit" value="Search"/>
                        </form>
                    </div>
                    <div class="widget commerce widget_product_categories">
                        <h3 class="widget-title">Категории</h3>
                        <?= Menu::widget([
                            'items' => $menuItems,
                            'options' => [
                                'class' => 'product-categories',
                            ],
                        ]) ?>
                    </div>
                    <div class="widget commerce widget_product_tag_cloud">
                        <h3 class="widget-title">Теги</h3>
                        <div class="tagcloud">
                            <?php foreach (Product::getTagsArray() as $key => $tag):?>
                                <a href="/catalog?tag=<?= $tag ?>"><?= $tag ?></a>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <?php if($noveltyProducts):?>
                        <div class="widget commerce widget_products">
                            <h3 class="widget-title">Новинки</h3>
                            <ul class="product_list_widget">
                                <?php foreach (array_values($noveltyProducts) as $model) :?>
                                    <li>
                                        <a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>">
                                            <?php
                                            $images = $model->images;
                                            if (isset($images[0])) {
                                                echo Html::img($images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt' => $model->title]);
                                            }
                                            ?>
                                            <span class="product-title"><?= $model->title ?></span>
                                        </a>
                                        <span class="amount"><?= (int)$model->price ?><i class="fa fa-ruble"></i></span>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Все лучшее для оформления -</h4>
            <h3>Ищите здесь</h3>
        </div>
    </div>
</div>
