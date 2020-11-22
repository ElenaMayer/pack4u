<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Order;
use common\models\Payment;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Заказ #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?> <?php if($model->is_ul):?><span class="red">ЮЛ</span><?php endif;?></h1>

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

    <div id="order_id" data-id="<?=$model->id?>"></div>

    <?php
    $attr = [
        'id',
        [
            'attribute' => 'status',
            'value' => function ($model) {
                return isset(Order::getStatuses()[$model->status]) ? Order::getStatuses()[$model->status] : $model->status;
            },
        ],
        'fio',
        'phone',
    ];
    if($model->email)
        $attr[] = 'email:email';
    if($model->is_ul)
        $attr[] = 'ul_requisites';
    $attr[] = [
            'attribute' => 'shipping_method',
            'value' => function ($model) {
                return Order::getShippingMethodsLite()[$model->shipping_method];
            },
        ];
    $attr[] = 'city';
    if($model->shipping_method == 'self'){
//        $attr[] = [
//            'attribute' => 'pickup_time',
//            'value' => function ($model) {
//                if(isset(Yii::$app->params['pickup_time'][$model->pickup_time])){
//                    return Yii::$app->params['pickup_time'][$model->pickup_time];
//                } else {
//                    return $model->pickup_time;
//                }
//            },
//        ];
    }
    if($model->address) {
        $attr[] = [
            'attribute' => 'address',
            'value' => function ($model) {
                return $model->address;
            },
        ];
    }
    $attr[] = [
        'attribute' => 'payment_method',
        'value' => function ($model) {
            return Order::getPaymentMethods()[$model->payment_method];
        },
    ];
//    $attr[] = [
//        'attribute' => 'payment_url',
//        'format' => 'html',
//        'value' => function ($model) {
//            if($model->payment_method == 'online' && $model->payment != 'succeeded')
//                return '<a class="get_payment_url" href="#">Получить ссылку</a>';
//            else
//                return '';
//        },
//    ];
    if($model->shipping_number) {
        $attr[] = [
            'attribute' => 'shipping_number',
            'format' => 'html',
            'value' => function ($model) {
                if ($model->shipping_number) {
                    if ($model->shipping_method == 'rp') {
                        $href = 'https://www.pochta.ru/tracking#' . $model->shipping_number;
                    } elseif ($model->shipping_method == 'tk') {
                        $href = 'https://www.cdek.ru/ru/tracking?order_id=' . $model->shipping_number;
                    }
                    return '<a target="_blank" href=' . $href . '>' . $model->shipping_number . '</a>';
                } else {
                    return $model->shipping_method;
                }
            },
        ];
    }
    if($model->notes)
        $attr[] = 'notes:ntext';
    $attr[] = 'created_at:date';
    $attr[] = 'updated_at:date';
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attr,
    ]) ?>
    <?= $this->render('_items_form', [
        'model' => $model,
    ]) ?>
</div>
