<?php
use yii\helpers\Html;
use yii\widgets\Menu;
use common\models\StaticFunction;
use yii\widgets\LinkPager;

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
                        <span class="noo-list"><a href="shop-list.html"><i class="fa fa-th-list"></i></a></span>
                        <span class="noo-grid active"><i class="fa fa-th-large"></i></span>
                    </div>
                    <form class="commerce-ordering">
                        <select name="orderby" class="orderby" id="p_sort_by" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'popular') ?>" <?php if(!Yii::$app->request->get('order') || Yii::$app->request->get('order') == 'popular'):?>selected="selected"<?php endif;?>>По популярности</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'novelty') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'novelty'):?>selected="selected"<?php endif;?>>По новинкам</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'price') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'price'):?>selected="selected"<?php endif;?>>По возрастанию цены</option>
                        </select>
                    </form>
                </div>
                <div class="products row product-grid">
                    <?php foreach (array_values($models) as $index => $model) :?>
                        <?= $this->render('_product', ['model'=>$model, 'category' => $category]); ?>
                    <?php endforeach;?>
                </div>
                <?php echo LinkPager::widget([
                    'pagination' => $pagination,
                    'options' => [
                        'class' => 'pagination list-center',
                    ],
                    'pageCssClass' => 'page-numbers',
                    'activePageCssClass' => 'current',
                    'prevPageCssClass' => 'page-numbers',
                    'nextPageCssClass' => 'page-numbers next',
                    'prevPageLabel' => '<i class="fa fa-long-arrow-left"></i>',
                    'nextPageLabel' => '<i class="fa fa-long-arrow-right"></i>',
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
                    <div class="widget commerce widget_price_filter">
                        <h3 class="widget-title">Фильтр по цене</h3>
                        <form>
                            <div class="price_slider_wrapper">
                                <div class="price_slider" style="display:none;"></div>
                                <div class="price_slider_amount">
                                    <input type="text" id="min_price" name="min_price" value="" data-min="0" placeholder="Min price"/>
                                    <input type="text" id="max_price" name="max_price" value="" data-max="15" placeholder="Max price"/>
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
                        <h3 class="widget-title">Тэги</h3>
                        <div class="tagcloud">
                            <a href="#">apple</a>
                            <a href="#">bread</a>
                            <a href="#">broccoli</a>
                            <a href="#">brown bread</a>
                            <a href="#">carrot</a>
                            <a href="#">celery</a>
                            <a href="#">cookie</a>
                            <a href="#">cucumbers</a>
                            <a href="#">detox juicing</a>
                            <a href="#">french bread</a>
                            <a href="#">fruits</a>
                            <a href="#">green apple</a>
                            <a href="#">lemon</a>
                            <a href="#">lime</a>
                            <a href="#">mango</a>
                        </div>
                    </div>
                    <div class="widget commerce widget_products">
                        <h3 class="widget-title">Популярное</h3>
                        <ul class="product_list_widget">
                            <li>
                                <a href="shop-detail.html">
                                    <img width="100" height="100" src="images/product/product_100x100.jpg" alt="" />
                                    <span class="product-title">French Bread</span>
                                </a>
                                <span class="amount">&#36;10.00</span>
                            </li>
                            <li>
                                <a href="shop-detail.html">
                                    <img width="100" height="100" src="images/product/product_100x100.jpg" alt="" />
                                    <span class="product-title">Cookie</span>
                                </a>
                                <span class="amount">&#36;15.00</span>
                            </li>
                            <li>
                                <a href="shop-detail.html">
                                    <img width="100" height="100" src="images/product/product_100x100.jpg" alt="" />
                                    <span class="product-title">Brown Bread</span>
                                </a>
                                <span class="amount">&#36;12.00</span>
                            </li>
                            <li>
                                <a href="shop-detail.html">
                                    <img width="100" height="100" src="images/product/product_100x100.jpg" alt="" />
                                    <span class="product-title">Apples</span>
                                </a>
                                <span class="amount">&#36;3.95</span>
                            </li>
                            <li>
                                <a href="shop-detail.html">
                                    <img width="100" height="100" src="images/product/product_100x100.jpg" alt="" />
                                    <span class="product-title">Pomegranates</span>
                                </a>
                                <span class="amount">&#36;3.90</span>
                            </li>
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