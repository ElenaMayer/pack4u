<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Способы оплаты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pt-10 pb-10">
    <div class="container">
        <div class="row about">
            <div class="col-sm-12">
                <h1>Оплата заказа</h1>
                <p><i class="fa fa-angle-right"></i>Минимальная сумма заказа <b><?=Yii::$app->params['orderMinSum'];?> рублей.</b></p>
                <p><i class="fa fa-angle-right"></i>Отправка в другие города производится только после <b>100% предоплаты.</b></p>

                <h3>Наличный расчет</h3>
                <p><i class="fa fa-angle-right"></i>Вы можете оплатить заказ наличными при получении в пункте выдачи (только <b>для Новосибирска</b>)</p>

                <h3>Оплата онлайн на сайте</h3>
                <p><i class="fa fa-angle-right"></i>Оплату можно произвести непостредственно после оформления заказа на сайте.</p>

                <p><i class="fa fa-angle-right"></i>Любой из видов оплаты Вы можете выбрать самостоятельно при оформлении заказа.</p>
                <p>Удачных покупок!</p>
            </div>
        </div>
    </div>
</div>
