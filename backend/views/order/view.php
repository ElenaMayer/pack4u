<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
            'created_at',
            'updated_at',
            'fio',
            'address',
            'phone',
            'email:email',
            'notes:ntext',
            'status',
            'shipping_cost',
            'city',
            'shipping_method',
            'payment_method',
            'tk',
            'rcr',
        ],
    ]) ?>
    <h2>Заказ:</h2>
    <?php
    $sum = 0;
    foreach ($model->orderItems as $item): ?>
        <?php $sum += $item->quantity * $item->price ?>
        <li><?= Html::encode($item->title .' (Арт. '. $item->product->article .')' . $item->quantity . ' x ' . (int)$item->price . ' руб.') ?></li>
    <?php endforeach ?>
    </ul>

    <p><string>Итого: </string> <?php echo $sum?> руб.</p>

</div>
