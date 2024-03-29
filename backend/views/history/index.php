<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/product/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'История товаров';
?>
<div class="product-index">

    <h1><a href="/product/view?id=<?=$model->id?>"><?= Html::encode($model->title) ?></a></h1>

    <div class="product-images">
        <?php foreach ($model->images as $image):?>
            <?= Html::img($image->getUrl('small'));?>
        <?php endforeach;?>
    </div>

    <div class="pb-2"></div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute'=>'title',
                'value' => function ($model) {
                    return $model->getTitleStr();
                },
            ],
            [
                'attribute'=>'Название',
                'value' => function ($model) {
                if($model->diversity_id)
                    return $model->diversity->title;
                else
                    return $model->product->title;
                },
            ],
            [
                'attribute'=>'Автор',
                'value' => function ($model) {
                    if($model->user_id)
                        return $model->user->fio;
                    else
                        return '-';
                },
            ],
            [
                'format' => 'html',
                'attribute'=>'Заказ',
                'value' => function ($model) {
                    if($model->order_id)
                        return Html::a($model->order_id, Url::to(['/order/view', 'id' => $model->order_id]));
                    else
                        return '-';
                },
            ],
            'count_old',
            'count_new',
            [
                'attribute'=>'Изменение',
                'value' => function ($model) {
                    $difference = $model->count_new - $model->count_old;
                    $sign = $difference > 0 ? '+' : '';
                    return $sign . $difference;
                },
            ],
            'created_at',
        ],
    ]); ?>

</div>
