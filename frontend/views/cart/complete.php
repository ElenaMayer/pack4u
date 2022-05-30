<?php

if($order->payment_method != 'online') {
    $this->title = 'Заказ успешно создан!';
} else {
    if($order->payment == 'succeeded') {
        $this->title = 'Оплата прошла успешно!';
    } else {
        $this->title = 'Ошибка оплаты';
    }
}
?>

<div class="pt-5 pb-5">
    <div class="container payment-result">
        <div class="row about">
            <div class="col-sm-12 text-center">
                <?php if($order->payment_method != 'online'):?>
                    <div class="row">
                        <h1>Заказ #<?=$order->id?> успешно создан!</h1>
                    </div>
                    <div class="pb-5"></div>
                    <div class="row result-img">
                        <img src="/images/success.png?1">
                    </div>
                    <div class="pb-5"></div>
                    <div class="row">
                        <?php if($order->user_id):?>
                            <h3>Благодарим за заказ! Подробную информацию можно посмотреть в личном кабинете.</h3>
                        <?php else:?>
                            <h3>Благодарим за заказ! В течении трех дней отправим заказ и напишем вам номер для отслеживания.</h3>
                        <?php endif;?>
                    </div>
                <?php else:?>

                    <?php if($order->payment == 'succeeded'):?>
                        <div class="row">
                            <h1>Оплата заказа #<?=$order->id?> прошла успешно!</h1>
                        </div>
                        <div class="pb-5"></div>
                        <div class="row">
                            <img src="/images/success.png?1">
                        </div>
                        <div class="pb-5"></div>
                        <div class="row">
                            <?php if($order->user_id):?>
                                <h3>Благодарим за заказ! Подробную информацию можно посмотреть в личном кабинете.</h3>
                                <h3>После отправки заказы мы пришлем Вам номер для отслеживания.<br>
                                    Подробную информацию можно посмотреть в личном кабинете.
                                </h3>
                            <?php else:?>
                                <h3>Благодарим за заказ! В течении трех дней отправим заказ и пришлем вам номер для отслеживания.</h3>
                            <?php endif;?>
                        </div>
                    <?php else:?>
                        <div class="row">
                            <h1>Оплата заказа #<?=$order->id?> отклонена.</h1>
                        </div>
                        <div class="pb-5"></div>
                        <div class="row">
                            <img src="/images/fail.png?1">
                        </div>
                        <div class="pb-5"></div>
                        <div class="row">
                            <h3>К сожалению во время оплаты произошла ошибка.</h3>
                            <?php if($paymentUrl):?>
                                <h3><a href="<?=$paymentUrl?>">Оплатить повторно</a></h3>
                            <?php endif;?>
                        </div>
                    <?php endif;?>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>