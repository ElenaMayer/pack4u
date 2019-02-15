<?php
use frontend\assets\ContactAsset;

ContactAsset::register($this);

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pt-10 pb-10">
    <div class="row">
        <div class="google-map">
            <div id="googleMap" data-icon="images/icon_location.png" data-lat="<?= Yii::$app->params['googleLat'] ?>" data-lon="<?= Yii::$app->params['googleLon'] ?>"></div>
            <div class="noo-address-info-wrap">
                <div class="container">
                    <div class="address-info">
                        <h3>Контакты</h3>
                        <p>
                            Если у Вас возикли вопросы, Вы может написать в социальных сетях или связаться с нами по телефону, электронной почте или заглянуть к нам в гости.
                        </p>
                        <ul>
                            <li>
                                <i class="fa fa-map-marker"></i>
                                <span><?= Yii::$app->params['address'] ?></span>
                            </li>
                            <li>
                                <i class="fa fa-envelope"></i>
                                <span><a href="mailto:<?= Yii::$app->params['email'] ?>"><?= Yii::$app->params['email'] ?></a></span>
                            </li>
                            <li>
                                <i class="fa fa-phone"> </i>
                                <span><a href="tel:<?= Yii::$app->params['phone1'] ?>"><?= Yii::$app->params['phone1'] ?></a></span>
                            </li>
                            <li>
                                <i class="fa fa-phone"> </i>
                                <span><a href="tel:<?= Yii::$app->params['phone2'] ?>"><?= Yii::$app->params['phone2'] ?></a></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>