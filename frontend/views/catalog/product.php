<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use \common\models\Product;
use frontend\assets\ProductAsset;

ProductAsset::register($this);

/* @var $this yii\web\View */
$title = $product->title;
Yii::$app->view->registerMetaTag(['name' => 'description','content' => $product->description]);
$diversityId = ($product->diversity && $diversity && $diversity->id) ? $diversity->id : null;
if($diversityId) $title .= " " . $diversity->title;
$subcategory = $product->getSubcategory();
$this->params['breadcrumbs'][] = ['label' => $category->title, 'url' => ['/catalog/' . $category->slug]];
if($subcategory){
    $this->params['breadcrumbs'][] = ['label' => $subcategory->title, 'url' => ['/catalog/' . $subcategory->slug]];
}
$this->params['breadcrumbs'][] = $title;
$this->title = Html::encode($title . ' ' . ($product->size ? $product->size . 'см':''));
$images = $product->images;
?>
<meta property="og:title" content="<?=$title?>"/>
<meta property="og:description" content="<?=$product->description?>"/>
<?php if($images[0]):?>
    <meta property="og:image" content="<?=$images[0]->getUrl()?>"/>
<?php endif;?>
<meta property="og:type" content="website"/>
<meta property="og:url" content= "https://<?=Yii::$app->params['domain']?>/catalog/<?=$category->slug?>/<?=$product->id?>" />
<meta property="product:availability" content="<?php if($product->getIsInStock()):?>in stock<?else:?>out of stock<?php endif;?>">
<meta property="product:condition" content="new">
<meta property="product:retailer_item_id" content="facebook_<?= $category->slug ?>_<?= $product->article?>">
<meta property="product:price:amount" content="<?=$product->getPrice()?>">
<meta property="product:price:currency" content="RUB">
<meta property="product:item_group_id" content="fb_<?= $category->slug ?>">
<meta property="product:retailer_item_id" content="facebook_<?= $category->slug ?>_<?= $product->article?>">
<div class="commerce single-product noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-9">
                <div class="product">
                    <div id="product-data" class="single-inner">
                        <?= $this->render('_product_data', [
                            'category' => $category,
                            'product' => $product,
                            'diversity' => $diversity,
                            'diversityId' => $diversityId,
                        ]); ?>
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
                                                echo Html::img($images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt' => $model->title . ' ' . $model->size. 'см']);
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
