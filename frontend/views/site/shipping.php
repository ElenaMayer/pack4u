<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Информация о доставке';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pt-10 pb-10">
    <div class="container">
        <div class="row about">
            <div class="col-sm-12">

                <h1>Доставка по Новосибирску</h1>
                <h3>Самовывоз</h3>
                <p><i class="fa fa-angle-right"></i>Самовывоз осуществляется <b>БЕСПЛАТНО</b>, во время выдачи заказов по адресу <b><?= Yii::$app->params['address'] ?></b>.</p>

                <h3>Курьер</h3>
                <p><i class="fa fa-angle-right"></i>Доставка осуществляется курьерской службой <b>Достависта</b>.</p>
                <p><i class="fa fa-angle-right"></i>Доставка <b>БЕСПЛАТНО</b> при заказе от <?= Yii::$app->params['freeShippingSum'] ?>руб.</p>
                <p><i class="fa fa-angle-right"></i>При заказе меньше <?= Yii::$app->params['freeShippingSum'] ?>руб. - доставка оплачивается курьеру при получении по тарифу курьерской службы.</b></p>
                <p><i class="fa fa-angle-right"></i>Расчет стоимости доставки можно произвести на <a href="<?=Yii::$app->params['dostavista_url']?>" target="_blank">сайте Достависты</a>.</p>

                <h1>Доставка по России</h1>

                <p><i class="fa fa-angle-right"></i>Доставка <b>БЕСПЛАТНО</b> при заказе от <?= Yii::$app->params['freeShippingSum'] ?>руб.</p>
                <p><i class="fa fa-angle-right"></i>Доставка при заказе меньше <?= Yii::$app->params['freeShippingSum'] ?>руб. - <b><?= Yii::$app->params['shippingCost'] ?>руб.</b></p>
                <p><i class="fa fa-angle-right"></i>Отправка заказа осуществляется после <b>100% оплаты</b> товара.</p>
                <p><i class="fa fa-angle-right"></i>Срок отправки заказа до 3-х рабочих дней с момента оплаты заказа.</p>

                <h3>Почта России</h3>
                <p><i class="fa fa-angle-right"></i>Доставка Почтой России осуществляется обычным отправлением до указанного почтового отделения.</p>
                <p><i class="fa fa-angle-right"></i>Отправка первым классом и курьерской службой EMS осуществляется за дополнительную плату.</p>
                <p><i class="fa fa-angle-right"></i>После отправки мы высылаем номер для отслеживания заказа.</p>

                <h3>Транспортная компания</h3>
                <p><i class="fa fa-angle-right"></i>Доставка осуществляется транспортной компанией СДЭК до пункта выдачи в вашем городе.</p>
                <p><i class="fa fa-angle-right"></i>Экспресс доставка и доставка до адреса осуществляется за дополнительную плату.</p>
                <p><i class="fa fa-angle-right"></i>Доставка крупногабаритных заказов осуществляется ТК Энергия.</p>
                <p><i class="fa fa-angle-right"></i>После отправки мы высылаем накладную с номером для отслеживания заказа.</p>

                <p class="contact">По всем вопросам покупки Вы можете обратиться к нам: Анна
                    <a href="tel:<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a> и Елена
                    <a href="tel:<?= Yii::$app->params['phone2'] ?>"><?= Yii::$app->params['phone2'] ?></a>
                </p>
            </div>
        </div>
    </div>
</div>
