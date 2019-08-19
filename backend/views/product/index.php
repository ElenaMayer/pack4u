<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p class="index-full">
        <?= Html::a('Полный вид', ['index_full'], ['class' => 'btn btn-info']) ?>
    </p>
    <p>
        <?= Html::a('Добавить товар', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'format' => 'image',
                'value'=>function($model) { return isset($model->images[0])?$model->images[0]->getUrl('small'):''; }
            ],
            'id',
            'article',
            'title',
            [
                'attribute'=>'category_id',
                'value' => function ($model) {
                    return empty($model->category_id) ? '-' : $model->category->title;
                },
                'filter' => Category::getCategoryList()
            ],
            [
                'attribute'=>'price',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->multiprice) {
                        return $model->getMultipricesStr();
                    } elseif($model->new_price) {
                        return '<s>' . $model->price . '</s> ' . $model->new_price;
                    } else {
                        return $model->price;
                    }
                },
            ],
            'size',
            [
                'attribute'=>'count',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->diversity) {
                        $result = '';
                        foreach ($model->diversities as $diversity){
                            $result .= $diversity->count . '/';
                        }
                        return trim($result, ' / ');
                    } else
                        return $model->count;
                },
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
            [
                'attribute'=>'is_novelty',
                'value' => function ($model) {
                    return $model->is_novelty ? 'Да' : 'Нет';
                },
                'filter' => [1 => 'Да', 0 => 'Нет']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'images' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-label="Image"></span>', Url::to(['image/index', 'id' => $model->id]));
                    }
                ],
            ],
        ],
    ]); ?>

</div>
