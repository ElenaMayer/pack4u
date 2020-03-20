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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return isset(Order::getStatuses()[$model->status]) ? Order::getStatuses()[$model->status] : $model->status;
                },
            ],
            'fio',
            'phone',
            'email:email',
            [
                'attribute' => 'shipping_method',
                'value' => function ($model) {
                    return Order::getShippingMethodsLite()[$model->shipping_method];
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
                        } elseif($model->shipping_method == 'courier' || $model->shipping_method == 'shipping' || $model->shipping_method == 'sdek_nsk') {
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
                    if($model->payment) {
                        $payment = Order::getPaymentTypes()[$model->payment];
                        if ($model->payment == 'canceled' && $model->payment_error) {
                            $payment .= ' (' . Payment::getErrorDesc($model->payment_error) . ')';
                        }
                        return $payment;
                    } else {
                        return 'Нет';
                    }
                },
            ],
            [
                'attribute' => 'payment_url',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->payment_method == 'card' && $model->payment != 'succeeded')
                        return '<a class="get_payment_url" href="#">Получить ссылку</a>';
                    else
                        return '';
                },
            ],
            [
                'attribute' => 'shipping_number',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->shipping_number){
                        if($model->shipping_method == 'rp'){
                            $href = 'https://www.pochta.ru/tracking#'.$model->shipping_number;
                        }
//                        elseif($model->shipping_method == 'tk' && $model->tk == 'sdek'){
//                            $href = 'https://www.pochta.ru/tracking#'.$model->shipping_number;
//                        }
                        return '<a href=' . $href . " target='_blank'>" . $model->shipping_number . '</a>';
                    } else {
                        $model->shipping_method;
                    }
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
