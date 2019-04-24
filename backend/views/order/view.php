<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use kartik\select2\Select2;

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
            ]: ($model->shipping_method == 'rcr' ?
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
            ])),
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
    <a class="btn btn-success add-item-link">Добавить позицию</a><h2>Заказ:</h2>
    <div class="add-item-form hide">
        <?php $form = ActiveForm::begin();
        $orderItem = new OrderItem(); ?>

        <?= $form->field($orderItem, 'product_id')->widget(Select2::classname(), [
            'options' => [
                'placeholder' => Yii::t('app','Выберите товар ...'),
            ],
            'data'=>Product::getProductArr(),
        ]) ?>

        <?= $form->field($orderItem, 'quantity')->textInput(['step' => 1, 'min' => 1, 'value' => 1]) ?>
        <div class="form-group">
            <?= Html::submitButton('Добавить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table class="table table-striped table-bordered detail-view">
        <tr>
            <th>Фото</th><th>Товар</th><th>Цена</th><th>Количество</th><th>Всего</th><th></th>
        </tr>
        <?php
        if($model->orderItems):
            foreach ($model->getSortOrderItems() as $item): ?>
                <tr>
                    <td>
                        <div class="product-image">
                            <?php if($item->product_id):?>
                                <a href="/product/view?id=<?= $item->product->id?>">
                                    <?= Html::img($item->product->images[0]->getUrl('small'));?>
                                </a>
                            <?php else:?>
                                <?= Html::img('/images/box.png');?>
                            <?php endif;?>
                        </div>
                    </td>
                    <td>
                        <?php echo $item->title . ' ' . ($item->product_id ? $item->product->size . 'см (Арт. '. $item->product->article .')' : '');?>
                    </td>
                    <td>
                        <input type="text" value="<?= (int)$item->price?>" class="cartitem_price_value m_input">
                        <?= Html::button('Ок', [
                            'class' => 'btn btn-success update_price',
                        ]) ?>
                    </td>
                    <td>
                        <input type="text" value="<?= $item->quantity?>" class="cartitem_qty_value m_input">
                        <input type="hidden" value="<?= $item->id?>" class="cartitem_id">
                        <?= Html::button('Ок', [
                            'class' => 'btn btn-success update_qty',
                        ]) ?>
                    </td>
                    <td>
                        <?= (int)($item->price * $item->quantity) . ' руб.'?>
                    </td>
                    <td>
                        <?= Html::a('x', ['delete_item', 'id' => $item->id], [
                            'class' => 'btn btn-danger',
                        ]) ?>
                    </td>
                </tr>
            <?php endforeach ?>
            <tr>
                <td>
                    <p><string>Вес: </string> <?= $model->getWeight()?> кг</p>
                </td>
            </tr>
            <tr>
                <td>
                    <p><string>Итого: </string> <?= $model->getSubCost()?> руб.</p>
                </td>
            </tr>
            <?php if($model->discount):?>
                <tr>
                    <td>
                        <p><string>Скидка: </string> <?= $model->discount?>%</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><string>Итого со скидкой: </string> <?= $model->getCostWithDiscount() ?> руб.</p>
                    </td>
                </tr>
            <?php endif;?>
            <?php if($model->shipping_cost):?>
                <tr>
                    <td>
                        <p><string>Доставка: </string> <?= $model->shipping_cost?> руб.</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><string>К оплате: </string> <?= $model->getCost()?> руб.</p>
                    </td>
                </tr>
            <?php endif;?>
        <?php endif;?>
    </table>
</div>
