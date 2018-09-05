<?php
use yii\widgets\Menu;
use common\models\StaticFunction;
use \common\models\Product;
use yii\helpers\Html;
?>

<div class="noo-sidebar col-md-3">
    <div class="noo-sidebar-wrap">
        <div class="widget commerce mobile-filter">
            <a class="mobile-filter-btn"><h3 class="widget-title">Фильтры<i class="fa fa-angle-down"></i></h3></a>
        </div>
        <div class="widget commerce widget_product_search">
            <h3 class="widget-title">Поиск</h3>
            <form action="/search">
                <input type="search" class="search-field" placeholder="Найти товар&hellip;" value="" name="s"/>
                <input type="submit" value="Search"/>
            </form>
        </div>
        <?php if(isset($menuItems)):?>
            <div class="widget commerce widget_product_categories mobile-filter-field">
                <h3 class="widget-title">Категории</h3>
                <?= Menu::widget([
                    'items' => $menuItems,
                    'options' => [
                        'class' => 'product-categories',
                    ],
                ]) ?>
            </div>
        <?php endif;?>
        <div class="widget commerce ht__pro__color mobile-filter-field">
            <h3 class="widget-title">Фильтр по цвету</h3>
            <ul class="ht__color__list">
                <li class="all">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','all')?>" title="все">все</a>
                </li>
                <li class="black <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'черный') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','черный')?>" title="черный">черный</a>
                </li>
                <li class="white <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'белый') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','белый')?>" title="белый">белый</a>
                </li>
                <li class="milk <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'молочный') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','молочный')?>" title="молочный">молочный</a>
                </li>
                <li class="lamon <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'крафт') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','крафт')?>" title="крафт">крафт</a>
                </li>
                <li class="gold <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'золото') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','золото')?>" title="золото">золото</a>
                </li>
            </ul>
            <ul class="ht__color__list">
                <li class="red <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'красный') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','красный')?>" title="красный">красный</a>
                </li>
                <li class="pink <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'розовый') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','розовый')?>" title="розовый">розовый</a>
                </li>
                <li class="lblue <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'голубой') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','голубой')?>" title="голубой">голубой</a>
                </li>
                <li class="green <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'зеленый') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','зеленый')?>" title="зеленый">зеленый</a>
                </li>
                <li class="violet <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'фиолетовый') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','фиолетовый')?>" title="фиолетовый">фиолетовый</a>
                </li>
                <li class="blue <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'синий') echo 'active'?>">
                    <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','синий')?>" title="синий">синий</a>
                </li>
            </ul>
        </div>
        <div class="widget commerce widget_product_tag_cloud mobile-filter-field">
            <h3 class="widget-title">Теги <a href="<?= StaticFunction::addGetParamToCurrentUrl('tag', 'all') ?>">Все</a></h3>
            <div class="tagcloud">
                <?php foreach (Product::getTagsArray() as $key => $tag):?>
                    <a <?php if(Yii::$app->request->get('tag') == $key):?>class="active"<?php endif;?> href="/catalog?tag=<?=$tag?>">
                        <?= $tag ?>
                    </a>
                <?php endforeach;?>
            </div>
        </div>
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
    </div>
</div>