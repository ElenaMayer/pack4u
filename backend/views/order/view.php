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
            'created_at:date',
            'updated_at:date',
            'fio',
            'phone',
            'email:email',
            'notes:ntext',
            'status',
            [
                'attribute' => 'shipping_method',
                'value' => function ($model) {
                    return Order::getShippingMethods()[$model->shipping_method];
                },
            ],
            [
                'attribute' => 'payment_method',
                'value' => function ($model) {
                    return Order::getPaymentMethods()[$model->payment_method];
                },
            ],
            [
                'attribute' => 'tk',
                'value' => function ($model) {
                    return $model->shipping_method == 'tk' ? Order::getTkList()[$model->tk] : '';
                },
            ],
            [
                'attribute' => 'rcr',
                'value' => function ($model) {
                    return $model->shipping_method == 'rcr' ? $model->rcr : '';
                },
            ],
            [
                'attribute' => 'address',
                'value' => function ($model) {
                    return $model->shipping_method == 'rp' ? $model->address : '';
                },
            ],
        ],
    ]) ?>
    <h2>Заказ:</h2>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <th>Фото</th><th>Товар</th><th>Цена</th><th>Количество</th><th>Всего</th>
        </tr>
        <?php
        $sum = 0;
        foreach ($model->orderItems as $item): ?>
            <tr>
                <?php $sum += $item->quantity * $item->price ?>
                <td>
                    <div class="product-image">
                        <a href="/catalog/<?= $item->product->category->slug?>/<?= $item->product->id?>">
                            <?= Html::img($item->product->images[0]->getUrl('small'));?>
                        </a>
                    </div>
                </td>
                <td>
                    <?= $item->title .' (Арт. '. $item->product->article .')'?>
                </td>
                <td>
                    <?= (int)$item->price . ' руб.'?>
                </td>
                <td>
                    <?= $item->quantity?>
                </td>
                <td>
                    <?= (int)($item->price * $item->quantity) . ' руб.'?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <p><string>Итого: </string> <?php echo $sum?> руб.</p>
            </td>
        </tr>
    </table>

</div>
