<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropDownList(Order::getStatuses()) ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'shipping_method')->dropDownList(Order::getShippingMethods()) ?>

    <?= $form->field($model, 'shipping_cost')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'tk')->dropDownList(Order::getTkList()) ?>

    <?= $form->field($model, 'rcr')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'payment_method')->dropDownList(Order::getPaymentMethods()) ?>

    <?= $form->field($model, 'payment')->dropDownList([1 => 'Есть', 0 => 'Нет']) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
