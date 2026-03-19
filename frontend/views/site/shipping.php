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
                <p>Стоимость доставки рассчитывается индивидуально, после оформления заказа. </b></p>

                <p class="contact">По всем вопросам покупки Вы можете обратиться к нам:
                    <a href="tel:<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a>
                </p>
            </div>
        </div>
    </div>
</div>
