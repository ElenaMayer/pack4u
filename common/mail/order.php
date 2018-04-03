<?php
/* @var $order common\models\Order */
use yii\helpers\Html;
?>

<h1>Новый заказ #<?= $order->id ?></h1>

<h2>Контакты</h2>

<ul>
    <li>ФИО: <?= Html::encode($order->phone) ?></li>
    <li>Телефон: <?= Html::encode($order->phone) ?></li>
    <?php if($order->email):?>
        <li>Email: <?= Html::encode($order->email) ?></li>
    <?php endif;?>
    <?php if($order->payment_method):?>
        <li>Доставка: <?= Order::getPaymentMethods()[$order->payment_method]; ?></li>
    <?php endif;?>
    <?php if($order->shipping_method):?>
        <li>Доставка: <?= Order::getShippingMethods()[$order->shipping_method]; ?></li>
    <?php endif;?>
    <?php if($model->shipping_method == 'tk' && $model->tk):?>
        <li>ТК: <?= $model->tk ?></li>
    <?php endif;?>
    <?php if($model->shipping_method == 'rcr' && $model->rcr):?>
        <li>РЦР: <?= $model->rcr?></li>
    <?php endif;?>
    <?php if($model->shipping_method == 'rp' && $model->address):?>
        <li>ТК: <?= $model->address ?></li>
    <?php endif;?>
</ul>

<h2>Комментарий</h2>
<?= Html::encode($order->notes) ?>

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

