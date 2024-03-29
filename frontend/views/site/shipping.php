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
                <h3>Стоимость доставки</h3>
                <p><b>БЕСПЛАТНАЯ</b> доставка осуществляется при заказе на сумму от <?= Yii::$app->params['freeShippingSum'] ?> рублей до пункта выдачи СДЭК; Стоимость курьерской доставки до двери оплачивается отдельно.</p>
                <p>Стоимость доставки при заказе на сумму меньше <?= Yii::$app->params['freeShippingSum'] ?> рублей расчитывается автоматически при оформлении заказа, в зависимости от выбранного города и способа доставки.</b></p>

                <h1>Доставка по Новосибирску</h1>
                <p><i class="fa fa-angle-right"></i>Доставка по Новосибирску осуществляется транспортной компанией СДЭК.</p>
                <p><i class="fa fa-angle-right"></i>Отправка заказа осуществляется после <b>100% оплаты</b> стоимости товара и доставки в срок до 3-х рабочих дней.</p>
                <p><i class="fa fa-angle-right"></i>Доставка курьером до Вашего адреса осуществляется за дополнительную плату.</p>
                <p><i class="fa fa-angle-right"></i>После отправки мы высылаем накладную с номером для отслеживания заказа.</p>

<!--                <h3>Самовывоз</h3>-->
<!--                <p><i class="fa fa-angle-right"></i>Самовывоз осуществляется <b>БЕСПЛАТНО</b>, во время работы пункта выдачи по адресу <b>--><?//= Yii::$app->params['address'] ?><!--</b>.</p>-->
<!--                <p><i class="fa fa-angle-right"></i>Обработка заказа начинается только после полной оплаты.</p>-->

                <!--h3>Курьер</h3>
                <p><i class="fa fa-angle-right"></i>Доставка осуществляется курьерской службой <b>Достависта</b>.</p>
                <p><i class="fa fa-angle-right"></i><b>БЕСПЛАТНАЯ</b> доставка осуществляется при оформлении заказа на сумму от <!--?= Yii::$app->params['freeShippingSum'] ?> рублей.</p>
                <p><i class="fa fa-angle-right"></i>При заказе на сумму меньше <!--?= Yii::$app->params['freeShippingSum'] ?> рублей - стоимость доставки оплачивается курьеру при получении сошласно тарифу курьерской службы.</b></p>
                <p><i class="fa fa-angle-right"></i>Расчет стоимости доставки можно произвести на <a href="<!--?=Yii::$app->params['dostavista_url']?>" target="_blank">сайте Достависты</a>.</p-->

                <h1>Доставка по России</h1>
                <p><i class="fa fa-angle-right"></i>Отправка заказа осуществляется после <b>100% оплаты</b> стоимости товара и доставки в срок до 3-х рабочих дней.</p>

                <h3>Почта России</h3>
                <p><i class="fa fa-angle-right"></i>Доставка Почтой России осуществляется обычным отправлением до указанного почтового отделения.</p>
                <p><i class="fa fa-angle-right"></i>Отправка первым классом и курьерской службой EMS осуществляется за дополнительную плату.</p>
                <p><i class="fa fa-angle-right"></i>После отправки мы высылаем трек - номер для отслеживания заказа.</p>

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
