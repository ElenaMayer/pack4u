<?php

use yii\widgets\Menu;
use common\models\Product;
use yii\helpers\Html;

$snippet = $data[0]->getSnippet();

$this->title = $snippet->title;
$this->params['breadcrumbs'][] = ['label' => 'Инструкции', 'url' => ['/instruction']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="single noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-9">
                <article>
                    <div class="blog-item">
                        <div class="content-featured">
                            <div class="noo-video-container">
                                <div class="video-inner full-screen">
                                    <iframe src="https://www.youtube.com/embed/<?= $data[0]->getId()?>" width="818" height="460" allowfullscreen></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
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
            <h4>- Красивая упаковка -</h4>
            <h3>Каждый день</h3>
        </div>
    </div>
</div>