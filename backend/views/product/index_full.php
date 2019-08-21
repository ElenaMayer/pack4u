<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары с расцветками';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p class="index-full">
        <?= Html::a('Краткий вид', ['index'], ['class' => 'btn btn-info']) ?>
    </p>
    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'html',
                'value'=>function($model) {
                        return Html::a('<img src="'.$model->getImageWithDiversity($model->diversity_id).'">', Url::to(['/product/update', 'id' => $model->id]));
                        }
            ],
            [
                'attribute'=>'id',
                'value' => function ($model) {
                    return ($model->diversity)?($model->id . '/'. $model->diversity_id):$model->id;
                },
            ],
            'article',
            'title',
            [
                'attribute'=>'category_id',
                'value' => function ($model) {
                    return empty($model->category_id) ? '-' : $model->category->title;
                },
                'filter' => Category::getCategoryList()
            ],
            'size',
            [
                'attribute'=>'count',
                'format' => 'html',
                'value' => function($model) { return $model->count; }
            ],
            [
                'attribute'=>'is_active',
                'value' => function ($model) {
                    return $model->is_active ? 'Да' : 'Нет';
                },
                'filter' => [1 => 'Да', 0 => 'Нет']
            ],
            [
                'attribute'=>'is_in_stock',
                'value' => function ($model) {
                    return $model->is_in_stock ? 'Да' : 'Нет';
                },
                'filter' => [1 => 'Да', 0 => 'Нет']
            ],
        ],
    ]); ?>

</div>
