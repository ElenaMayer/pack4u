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
                        <div class="images">
                            <?php $images = $product->images; ?>
                            <?php foreach ($images as $key => $image):?>
                            <div class="product-simple-image <?php if($key == 0):?>active<?php endif;?>">
                                <a href="<?= $image->getUrl()?>" data-rel="prettyPhoto[gallery]">
                                    <?= Html::img($image->getUrl(), ['width' => '300', 'height' => '381', 'alt'=>$product->title]);?>
                                </a>
                            </div>
                            <?php endforeach;?>
                        </div>
                        <div class="summary entry-summary">
                            <h1 class="product_title entry-title"><?= Html::encode($product->title) ?></h1>
                            <p class="price"><span class="amount"><?= (int)$product->price ?>₽</span></p>
                            <div class="product-status">
                                <?php if($product->is_in_stock):?><span class="green">В наличии</span><?php else:?><span class="red">Отсутствует</span><?php endif;?>
                                <span>-</span>
                                <small>Арт: <?= $product->article?></small>
                            </div>
                            <p class="description"><?= Markdown::process($product->description) ?></p>
                            <div class="product_meta">
                                <?php if($product->size):?>
                                    <span class="posted_in">Размер: <?= Html::encode($product->size) ?> см</span>
                                <?php endif;?>
                                <?php if($product->size):?>
                                    <span class="posted_in">Цвет: <?= Html::encode($product->color) ?></span>
                                <?php endif;?>
                                <span class="posted_in">Категория: <a href="/catalog/<?= $category->slug?>" title="<?= $category->title?>"><?= $category->title?></a></span>
                                <?php if($product->size):?>
                                    <span class="tagged_as">Тэги:
                                        <?php foreach ($product->getCurrentTagsArray() as $key => $tag):?>
                                            <a href="/tag/<?= $tag ?>"><?= $tag ?></a>
                                        <?php endforeach;?>
                                    </span>
                                <?php endif;?>
                            </div>
                            <form class="cart">
                                <div class="quantity">
                                    <input type="number" step="1" min="1" name="quantity" value="1" title="Qty" class="input-text qty text" size="4"/>
                                </div>
                                <?= Html::a('Add to cart', ['cart/add', 'id' => $product->id], ['class' => 'single_add_to_cart_button button'])?>
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
                                <?= $this->render('_product', ['model'=>$model, 'category' => $category]); ?>
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
                                <a href="/tag/<?= $tag ?>"><?= $tag ?></a>
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