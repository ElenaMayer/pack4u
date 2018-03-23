<?php
use yii\helpers\Html;
use yii\helpers\Markdown;
use yii\widgets\Menu;
use \common\models\Product;

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
<!--                                                <div class="easyzoom easyzoom--overlay">-->
                                                    <a href="<?= $image->getUrl()?>" title="<?= $product->title?>">
                                                        <?= Html::img($image->getUrl(), ['width' => '100%', 'alt'=>$product->title]);?>
                                                    </a>
<!--                                                </div>-->
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
                                                <?= Html::img($image->getUrl(), ['width' => '100%', 'alt'=>$product->title]);?>
                                            </div>
                                        <?php endforeach;?>
                                    </div>
                                </div><!-- /.swiper-container -->
                            </div><!-- /.product-slider-wrapper -->
                        </div>
                        <div class="summary entry-summary">
                            <h1 class="product_title entry-title"><?= Html::encode($product->title) ?></h1>
                            <div class="product-status">
                                <?php if($product->is_in_stock):?><span class="green">В наличии </span><?php else:?><span class="red">Отсутствует</span><?php endif;?>
                                <span>&nbsp;-&nbsp;</span>
                                <span>Арт: <?= $product->article?></span>
                            </div>
                            <p class="price"><span class="amount"><?= (int)$product->price ?>₽</span></p>
                            <p class="description"><?= $product->description ?></p>
                            <div class="product_meta">
                                <?php if($product->size):?>
                                    <span class="posted_in">Размер: <span class="value"><?= Html::encode($product->size) ?> см</span></span>
                                <?php endif;?>
                                <?php if($product->size):?>
                                    <span class="posted_in">Цвета: <span class="value"><?= Html::encode($product->color) ?></span></span>
                                <?php endif;?>
                                <span class="posted_in">Категория: <a href="/catalog/<?= $category->slug?>" title="<?= $category->title?>"><?= $category->title?></a></span>
                                <?php if($product->size):?>
                                    <span class="tagged_as">Тэги:
                                        <?php foreach ($product->getCurrentTagsArray() as $key => $tag):?>
                                            <a href="/catalog?tag=<?= $tag ?>"><?= $tag ?></a>
                                        <?php endforeach;?>
                                    </span>
                                <?php endif;?>
                            </div>
                            <form class="cart" action="/cart/add" method="get">
                                <div class="quantity">
                                    <input type="number" step="1" min="1" name="quantity" value="1" title="Количество" class="input-text qty text" size="4"/>
                                    <input type="hidden" name="id" value="<?= $product->id ?>"/>
                                </div>
                                <?= Html::button('В карзину', ['type' => 'submit', 'class' => 'single_add_to_cart_button button'])?>
                            </form>
                            <div class="yith-wcwl-add-to-wishlist">
                                <div class="yith-wcwl-add-button">
                                    <a href="#" class="add_to_wishlist"></a>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="related products">
                        <h2>Популярное</h2>
                        <div class="products row product-grid">
                            <?php foreach (array_values($relatedProducts) as $index => $model) :?>
                                <?= $this->render('_product', ['model'=>$model]); ?>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="noo-sidebar col-md-3">
                <div class="noo-sidebar-wrap">
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
                        <h3 class="widget-title">Тэги</h3>
                        <div class="tagcloud">
                            <?php foreach (Product::getTagsArray() as $key => $tag):?>
                                <a href="/catalog?tag=<?= $tag ?>"><?= $tag ?></a>
                            <?php endforeach;?>
                        </div>
                    </div>
                    <div class="widget commerce widget_products">
                        <h3 class="widget-title">Популярное</h3>
                        <ul class="product_list_widget">
                            <?php foreach (array_values($relatedProducts) as $model) :?>
                                <li>
                                    <a href="/catalog/<?= $model->category->slug?>/<?= $model->id?>">
                                        <?php
                                        $images = $model->images;
                                        if (isset($images[0])) {
                                            echo Html::img($images[0]->getUrl(), ['width' => '100', 'height' => '100', 'alt' => $model->title]);
                                        }
                                        ?>
                                        <span class="product-title"><?= $model->title ?></span>
                                    </a>
                                    <span class="amount"><?= (int)$model->price ?>₽</span>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Every day fresh -</h4>
            <h3>organic food</h3>
        </div>
        <img src="images/organici-love-me.png" class="noo-image-footer" alt="" />
    </div>
</div>
