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
                            <li>
                                <span>Время работы пункта выдачи:</span>
                            </li>
                            <?php foreach (Yii::$app->params['pickup_time'] as $time):?>
                                <li>
                                    <i class="fa fa-clock-o"> </i>
                                    <span><?= $time?></span>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>