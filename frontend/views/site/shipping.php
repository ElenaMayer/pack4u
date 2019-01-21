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
                <p>Самовывоз осуществляется <b>бесплатно</b>, по предварительной договоренности.</p>

                <h3>РЦР</h3>

                <p><i class="fa fa-angle-right"></i><b>РЦР (районные центры раздач)</b> – это система передачи заказов и личных вещей по городу Новосибирску,
                    прилегающим поселкам и ближнему межгороду, своего рода служба доставки, но не единая, а состоящая из отдельных пунктов и сетей.</p>
                <p><i class="fa fa-angle-right"></i><b>Список РЦР, тарифы, а также правила</b> получения товара можно посмотреть здесь: <a href="https://гдерцр.рф/" target="_blank">гдерцр.рф</a></p>
                <p><i class="fa fa-angle-right"></i>Доставка в пункт РЦР бесплатно, в течении 3-х рабочих дней с момента оплаты заказа.</p>
                <p><i class="fa fa-angle-right"></i>Оплата доставки через РЦР осуществляется <b>при получении</b>.</p>

                <h1>Доставка по всему миру</h1>

                <p><i class="fa fa-angle-right"></i>Мы осуществляем доставку во все регионы России и мира</p>
                <p><i class="fa fa-angle-right"></i>Доставка в регионы осуществляется Почтой России или транспортной компанией после <b>100% оплаты</b> товара.</p>
                <p><i class="fa fa-angle-right"></i>Доставка до терминала ТК или Почты России бесплатно, в течении 3-х рабочих дней с момента оплаты заказа.</p>
                <p><i class="fa fa-angle-right"></i>Мы сотрудничаем с ведущими транспортными компаниями России, которые доставляют грузы <b>до терминала</b> или <b>до Вашей двери</b>.</p>
                <p><i class="fa fa-angle-right"></i>Оплата за доставку до места назначения производит покупатель <b>согласно тарифу</b> транспортной компании.</p>
                <p><i class="fa fa-angle-right"></i>Дополнительная упаковки (если требуется), оплачивается покупателем перед отправкой заказа.</p>
                <p><i class="fa fa-angle-right"></i>После отправки мы высылаем номер для отслеживания посылки.</p>
                <p><i class="fa fa-angle-right"></i>Рассчитать стоимость доставки Вы можете самостоятельно, пройдя по ссылкам:</p>

                <p class="sub"><i class="fa fa-angle-right"></i><a href="https://www.dellin.ru/" target="_blank">Деловые линии</a></p>
                <p class="sub"><i class="fa fa-angle-right"></i><a href="https://www.cdek.ru" target="_blank">СДЭК</a></p>
                <p class="sub"><i class="fa fa-angle-right"></i><a href="https://pecom.ru/" target="_blank">ПЭК</a></p>
                <p class="sub"><i class="fa fa-angle-right"></i><a href="https://nrg-tk.ru/client/calculator/" target="_blank">Энергия</a></p>
                <p class="sub"><i class="fa fa-angle-right"></i><a href="https://www.pochta.ru/" target="_blank">Почта России</a></p>

                <p class="contact">По всем вопросам покупки Вы можете обратиться к нам: Анна
                    <a href="tel:<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a> и Елена
                    <a href="tel:<?= Yii::$app->params['phone2'] ?>"><?= Yii::$app->params['phone2'] ?></a></p>
            </div>
        </div>
    </div>
</div>
