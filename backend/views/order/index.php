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
        'columns' => [
            'id',
            'fio',
            'phone',
            'email:email',
            [
                'attribute'=>'status',
                'value' => function ($model) {
                    return $model->getStatuses()[$model->status];
                },
                'filter' => Order::getStatuses()
            ],
            [
                'header'=>'Сумма',
                'value' => function ($model) {
                    return $model->getCost();
                }
            ],
            [
                'attribute'=>'payment',
                'value' => function ($model) {
                    return $model->payment ? 'Есть' : 'Нет';
                },
                'filter' => [1 => 'Есть', 0 => 'Нет']
            ],
            'created_at:date',
            [
                'class' => 'yii\grid\ActionColumn'
            ],
        ],
    ]); ?>

</div>
