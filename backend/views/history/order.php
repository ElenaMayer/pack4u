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
                'format' => 'html',
                'attribute'=>'order_id',
                'value' => function ($model) {
                    return Html::a($model->order_id, Url::to(['/order/view', 'id' => $model->order_id]));
                },
            ],
            [
                'attribute'=>'Статус',
                'value' => function ($model) {
                    return $model->order->getStatuses()[$model->order->status];
                },
            ],
            [
                'attribute'=>'Заказчик',
                'value' => function ($model) {
                    return $model->order->fio;
                },
            ],
            'title',
            'quantity',
            [
                'attribute'=> 'price',
                'value' => function ($model) {
                    return (int)$model->price;
                },
            ],
            [
                'attribute'=>'Сумма',
                'value' => function ($model) {
                    return $model->price * $model->quantity;
                },
            ],
            [
                'attribute'=>'Дата',
                'format' => 'datetime',
                'value' => function ($model) {
                    return $model->order->created_at;
                },
            ],
        ],
    ]); ?>

</div>
