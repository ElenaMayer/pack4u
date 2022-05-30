<?php
use yii\helpers\Html;
use common\models\StaticFunction;
use yii\widgets\LinkPager;
use \common\models\Product;
use frontend\assets\CatalogAsset;

CatalogAsset::register($this);

/* @var $this yii\web\View */

if(isset($category->parent)){
    $title = $category->parent->title . ' - ' . $category->title;
    $this->params['breadcrumbs'][] = ['label' => $category->parent->title, 'url' => ['/catalog/' . $category->parent->slug]];
} else {
    $title = $category === null ? 'Каталог красивой упаковки' : $category->title;
}

Yii::$app->view->registerMetaTag(['name' => 'description','content' => $category === null ? Yii::$app->params['companyDesc'] : $category->description]);
$this->title = Html::encode($title);
if(isset($category->parent)){
    $this->params['breadcrumbs'][] = ['label' => $category->parent->title, 'url' => ['/catalog/' . $category->parent->slug]];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="commerce noo-shop-main product-catalog">
    <div class="container">
        <div class="row">
            <?= $this->render('_sidebar', [
                    'category' => $category,
                    'menuItems' => $menuItems,
                    'noveltyProducts' => Product::getNovelties(),
            ]); ?>
            <div class="noo-main col-md-9">
                <div class="noo-catalog">
                    <?php
                    $begin = $pagination->getPage() * $pagination->pageSize + 1;
                    $end = $begin + $pageCount - 1;
                    ?>
                    <p class="commerce-result-count">Товары с <?= $begin ?> по <?= $end ?> из <?= $pagination->totalCount ?></p>
                    <div class="product-style-control pull-right">
                        <span class="noo-list"><a><i class="fa fa-th-list"></i></a></span>
                        <span class="noo-grid active"><a><i class="fa fa-th-large"></i></a></span>
                    </div>
                    <form class="commerce-ordering">
                        <select name="orderby" class="orderby" id="p_sort_by" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'popular') ?>" <?php if(!Yii::$app->request->get('order') || Yii::$app->request->get('order') == 'popular'):?>selected="selected"<?php endif;?>>По популярности</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'article') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'article'):?>selected="selected"<?php endif;?>>По артикулу</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'price_lh') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'price_lh'):?>selected="selected"<?php endif;?>>По возрастанию цены</option>
                            <option value="<?= StaticFunction::addGetParamToCurrentUrl('order', 'price_hl') ?>" <?php if(Yii::$app->request->get('order') && Yii::$app->request->get('order') == 'price_hl'):?>selected="selected"<?php endif;?>>По убыванию цены</option>
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
                    'firstPageLabel' => true,
                    'firstPageCssClass' => 'page-numbers',
                    'lastPageLabel' => true,
                    'lastPageCssClass' => 'page-numbers',
                    'activePageCssClass' => 'current',
                    'prevPageCssClass' => 'page-numbers',
                    'nextPageCssClass' => 'page-numbers next',
                    'prevPageLabel' => '<',
                    'nextPageLabel' => '>',
                    'maxButtonCount' => 6
                ]); ?>
            </div>
        </div>
    </div>
</div>