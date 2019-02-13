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
                <h3>Оплата</h3>
                <p><i class="fa fa-angle-right"></i>Минимальная сумма заказа <b><?=Yii::$app->params['orderMinSum'];?> рублей.</b></p>
                <p><i class="fa fa-angle-right"></i>Отправка в другие города производится только после <b>100% предоплаты.</b></p>

                <h3>Наличный расчет</h3>
                <p>Наличный расчет при получении в пункте выдачи (только <b>для Новосибирска</b>)</p>

                <h3>Перевод на карту</h3>
                <p>Оплату можно произвести на карты следующих банков:</p>

                <p><i class="fa fa-angle-right"></i>Сбербанк</p>
                <p><i class="fa fa-angle-right"></i>Альфа-Банк</p>
                <p><i class="fa fa-angle-right"></i>БинБанк</p>
                <p><i class="fa fa-angle-right"></i>ВТВ24</p>
            </div>
        </div>
    </div>
</div>
