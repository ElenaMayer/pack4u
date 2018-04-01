<?php
/* @var $order common\models\Order */
use yii\helpers\Html;
?>

<h1>Новый заказ #<?= $order->id ?></h1>

<h2>Контакты</h2>

<ul>
    <li>Телефон: <?= Html::encode($order->phone) ?></li>
    <li>Email: <?= Html::encode($order->email) ?></li>
</ul>

<h2>Комментарий</h2>

<?= Html::encode($order->notes) ?>

<h2>Заказ</h2>

<ul>
<?php
$sum = 0;
foreach ($order->orderItems as $item): ?>
    <?php $sum += $item->quantity * $item->price ?>
    <li><?= Html::encode($item->title . ' x ' . $item->quantity . ' x ' . $item->price . ' руб.') ?></li>
<?php endforeach ?>
</ul>

<p><string>Итого: </string> <?php echo $sum?>$</p>

