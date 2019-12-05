<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Заказ #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверена? Я бы не стала этого делать :)',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return Order::getStatuses()[$model->status];
                },
            ],
            'fio',
            'phone',
            'email:email',
            [
                'attribute' => 'shipping_method',
                'value' => function ($model) {
                    return Order::getShippingMethods()[$model->shipping_method];
                },
            ],
            $model->shipping_method == 'tk' ? [
                'attribute' => 'tk',
                'value' => function ($model) {
                    return $model->shipping_method == 'tk' ? Order::getTkList()[$model->tk] . ' - ' . $model->city : '';
                },
            ]: ($model->shipping_method == 'self' ?
                [
                'attribute' => 'pickup_time',
                'value' => function ($model) {
                    if(isset(Yii::$app->params['pickup_time'][$model->pickup_time])){
                        return Yii::$app->params['pickup_time'][$model->pickup_time];
                    } else {
                        return $model->pickup_time;
                    }
                },
            ] : ($model->shipping_method == 'rcr' ?
                [
                    'attribute' => 'rcr',
                    'value' => function ($model) {
                        return $model->shipping_method == 'rcr' ? $model->rcr : '';
                    },
                ] : (
                [
                    'attribute' => 'address',
                    'value' => function ($model) {
                        if($model->shipping_method == 'rp'){
                            return $model->zip . ', ' . $model->address;
                        } elseif($model->shipping_method == 'courier') {
                            return $model->address;
                        }
                    },
                ]))),
            [
                'attribute' => 'payment_method',
                'value' => function ($model) {
                    return Order::getPaymentMethods()[$model->payment_method];
                },
            ],
            [
                'attribute'=>'payment',
                'value' => function ($model) {
                    return $model->payment ? 'Есть' : 'Нет';
                },
            ],
            'notes:ntext',
            'created_at:date',
            'updated_at:date',
        ],
    ]) ?>
    <?= $this->render('_items_form', [
        'model' => $model,
    ]) ?>
</div>
