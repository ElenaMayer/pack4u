<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить заказ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            return ['class' => $model->status];
        },
        'columns' => [
            'id',
            [
                'attribute'=>'fio',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->user_id){
                        $href = 'index?OrderSearch%5Buser_id%5D='.$model->user_id;
                        return '<a href=' . $href . '>' . $model->fio . '</a>';
                    } elseif (Order::isSameFioExist($model->fio)){
                        $href = 'index?OrderSearch%5Bfio%5D='.$model->fio;
                        return '<a href=' . $href . '>' . $model->fio . '</a>';
                    } else {
                        return $model->fio;
                    }
                },
            ],
            'phone',
            [
                'attribute'=>'status',
                'value' => function ($model) {
                    return $model->getStatuses()[$model->status];
                },
                'filter' => Order::getStatuses()
            ],
            [
                'attribute'=>'shipping_method',
                'value' => function ($model) {
                    if($model->shipping_method == 'tk') {
                        if($model->city)
                            return Order::getTkList()[$model->tk] . ' (' . $model->city . ')';
                        else
                            return Order::getTkList()[$model->tk];
                    }
                    elseif ($model->shipping_method == 'self') {
                        if($model->notes)
                            return "Самовывоз ($model->notes)";
                        elseif($model->pickup_time)
                            return "Самовывоз (" . Yii::$app->params['pickup_time'][$model->pickup_time] . ")";
                        else
                            return 'Самовывоз';
                    } elseif($model->shipping_method == 'rcr'){
                        if($model->rcr)
                            return "РЦР ($model->rcr)";
                        else
                            return 'РЦР';
                    } elseif($model->shipping_method == 'courier'){
                        if($model->notes)
                            return "Курьер ($model->notes)";
                        else
                            return 'Курьер';
                    }
                    else return Order::getShippingMethodsLite()[$model->shipping_method];
                },
                'filter' => Order::getShippingMethodsLite()
            ],
            [
                'header'=>'Сумма',
                'value' => function ($model) {
                    return $model->getCost();
                }
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>

</div>
