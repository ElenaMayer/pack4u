<?php
/* @var $order common\models\Order */

$timePV = '';
foreach(Yii::$app->params['pickup_time'] as $time){
    $timePV .= $time.', ';
}
$timePV = trim(trim($timePV));
?>

<h1>Заказ #<?= $order->id ?> успешно оплачен!</h1>

<ul style="list-style: none;">
    <li>Благодарим за заказ!</li>
    <?php if($order->shipping_method == 'self'):?>
        <li>Вы свяжемся с Вами для согласования деталей доставки!</li>
    <?php else:?>
        <li>Мы отправим заказ в течении трех рабочих дней и пришлем Вам
            <?php if($order->shipping_method == 'rp'):?>
                накладную.
            <?php else:?>
                номер для отслеживания.
            <?php endif;?></li>
    <?php endif;?>
    <li>Если у Вас возникли вопросы, Вы можете их задать по телефону и Whatsapp <a href="tel:<?= Yii::$app->params['phone2'] ?>"><?= Yii::$app->params['phone2'] ?></a></li>
</ul>
