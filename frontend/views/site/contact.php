<?php
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use frontend\assets\ContactAsset;

ContactAsset::register($this);

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pt-10 pb-10">
    <div class="container">
        <div class="row">
            <div class="noo_image_left col-sm-6">
                <div class="noo-image-signature">
                    <div class="img-background-color"></div>
                    <div class="img-background-sign style-2"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <?php $form = ActiveForm::begin(['id' => 'contact-form', 'options' => ['class' => 'contact-form']]); ?>
                    <h3>Напишите нам</h3>
                    <p class="hint">
                        Если у Вас возикли вопросы, Вы может написать в социальных сетях или связаться с нами через эту форму.
                    </p>
                    <div class="row">
                        <div class="col-md-8 col-sm-12">
                            <div class="form-control-wrap your-name">
                                <?= $form->field($model, 'name', ['template'=>'{input}{error}'])->textInput(['class' => "form-control", 'placeholder' => $model->getAttributeLabel( 'name' )]) ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-control-wrap your-email">
                                <?= $form->field($model, 'email', ['template'=>'{input}{error}'])->textInput(['placeholder' => $model->getAttributeLabel( 'email' )]) ?>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-control-wrap your-phone">
                                <?= $form->field($model, 'phone', ['template'=>'{input}{error}'])->textInput(['placeholder' => $model->getAttributeLabel( 'phone' )]) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-control-wrap your-message">
                                <?= $form->field($model, 'body', ['template'=>'{input}{error}'])->textarea(['placeholder' => $model->getAttributeLabel( 'body' ), 'rows' => 10]) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-control-wrap your-message">
                        <?= $form->field($model, 'verifyCode', ['template'=>'{input}{error}'])
                            ->widget(Captcha::className(), ['template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',])?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <input type="submit" value="Отправить" class="form-control submit btn-primary"/>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<div class="google-map">
    <div id="googleMap" data-icon="images/icon_location.png" data-lat="<?= Yii::$app->params['googleLat'] ?>" data-lon="<?= Yii::$app->params['googleLon'] ?>"></div>
    <div class="noo-address-info-wrap">
        <div class="container">
            <div class="address-info">
                <h3>Контакты</h3>
                <p>
                    Так же Вы можете связаться с нами по телефону, электронной почте или заглянуть к нам в гости.
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
                        <span><?= Yii::$app->params['phone1'] ?></span>
                    </li>
                    <li>
                        <i class="fa fa-phone"> </i>
                        <span><?= Yii::$app->params['phone2'] ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>