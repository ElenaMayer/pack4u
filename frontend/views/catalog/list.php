<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use common\models\StaticFunction;
use yii\widgets\LinkPager;
use \common\models\Product;
use frontend\assets\CatalogAsset;

CatalogAsset::register($this);

/* @var $this yii\web\View */
$title = $category === null ? 'Каталог' : $category->title;
$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="commerce noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-9">
                <div class="noo-catalog">
                    <?php
                    $begin = $pagination->getPage() * $pagination->pageSize + 1;
                    $end = $begin + $pageCount - 1;
                    ?>
                    <p class="commerce-result-count">Товар с <?= $begin ?> по <?= $end ?> из <?= $pagination->totalCount ?></p>
                    <div class="product-style-control pull-right">
                        <span class="noo-list"><a><i class="fa fa-th-list"></i></a></span>
                        <span class="noo-grid active"><a><i class="fa fa-th-large"></i></a></span>
                    </div>
                    <form class="commerce-ordering">
                        <select name="orderby" class="orderby" id="p_sort_by" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'popular') ?>" <?php if(!Yii::$app->request->get('order') || Yii::$app->request->get('order') == 'popular'):?>selected="selected"<?php endif;?>>По популярности</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'price_lh') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'price_lh'):?>selected="selected"<?php endif;?>>По возрастанию цены</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'price_hl') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'price_hl'):?>selected="selected"<?php endif;?>>По убыванию цены</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'novelty') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'novelty'):?>selected="selected"<?php endif;?>>По новинкам</option>
                        </select>
                    </form>
                </div>
                <div class="products row product-grid">
                    <?php foreach (array_values($models) as $index => $model) :?>
                        <?= $this->render('_product', ['model'=>$model]); ?>
                    <?php endforeach;?>
                </div>
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => [
                        'class' => 'pagination list-center',
                    ],
                    'pageCssClass' => 'page-numbers',
                    'firstPageLabel' => '<<',
                    'firstPageCssClass' => 'page-numbers',
                    'lastPageLabel' => '>>',
                    'lastPageCssClass' => 'page-numbers',
                    'activePageCssClass' => 'current',
                    'prevPageCssClass' => 'page-numbers',
                    'nextPageCssClass' => 'page-numbers next',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'maxButtonCount' => 6
                ]); ?>
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
                    <div class="widget commerce ht__pro__color">
                        <h3 class="widget-title">Фильтр по цвету</h3>
                        <ul class="ht__color__list">
                            <li class="all <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'все') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','все')?>" title="все">все</a>
                            </li>
                            <li class="black <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'черный') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','черный')?>" title="черный">черный</a>
                            </li>
                            <li class="white <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'белый') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','белый')?>" title="белый">белый</a>
                            </li>
                            <li class="gold <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'золото') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','золото')?>" title="золото">золото</a>
                            </li>
                            <li class="lamon <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'крафт') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','крафт')?>" title="крафт">крафт</a>
                            </li>
                            <li class="broun <?php if(Yii::$app->request->get('color') && Yii::$app->request->get('color') == 'коричневый') echo 'active'?>">
                                <a href="<?= StaticFunction::addGetParamToCurrentUrl('color','коричневый')?>" title="коричневый">коричневый</a>
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
                    <div class="widget commerce widget_price_filter">
                        <h3 class="widget-title">Фильтр по цене</h3>
                        <form method="get">
                            <div class="price_slider_wrapper">
                                <div class="price_slider" style="display:none;"></div>
                                <div class="price_slider_amount">
                                    <?php $urlParams = StaticFunction::getParamArrayFromCurrentUrl() ?>
                                    <?php foreach ($urlParams as $urlName => $urlValue):?>
                                        <?php if($urlName != 'min_price' && $urlName != 'max_price'):?>
                                            <input type="hidden" name="<?= $urlName ?>" value="<?= $urlValue ?>">
                                        <?php endif;?>
                                    <?php endforeach;?>
                                    <input type="text" id="min_price" name="min_price" value="<?= Yii::$app->request->get('min_price')?Yii::$app->request->get('min_price'):''?>" data-min="0" placeholder="Минимальная"/>
                                    <input type="text" id="max_price" name="max_price" value="<?= Yii::$app->request->get('max_price')?Yii::$app->request->get('max_price'):''?>" data-max="<?= Product::find()->max('price'); ?>" placeholder="Максимальная"/>
                                    <button type="submit" class="button">Найти</button>
                                    <div class="price_label" style="display:none;">
                                        Цена: <span class="from"></span> &mdash; <span class="to"></span>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="widget commerce widget_product_tag_cloud">
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
                </div>
            </div>
        </div>
    </div>
</div>