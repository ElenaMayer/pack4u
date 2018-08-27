<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use \common\models\Product;

/* @var $this yii\web\View */

$this->title = 'Поиск по запросу "'.$_GET['s'] .'""';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="commerce single-product noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-9">
                <div class="row">
                    <div class="widget commerce widget_product_search">
                        <form action="/search">
                            <input type="search" class="search-field" placeholder="Найти товар&hellip;" value="" name="s"/>
                            <input type="submit" value="Search"/>
                        </form>
                    </div>
                    <h3 class="entry-title">Результаты по запросу "<span class="archive-name"><?= $_GET['s'] ?></span>"</h3>
                    <div class="products row product-grid">
                        <?php foreach ($snippets as $id => $snippet) :?>
                            <?php $model = Product::findOne($id)?>
                            <?= $this->render('../catalog/_product', ['model'=>$model]); ?>
                        <?php endforeach;?>
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