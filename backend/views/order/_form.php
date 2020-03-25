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

    <?= $form->field($model, 'is_ul')->checkbox() ?>

    <?= $form->field($model, 'shipping_method')->dropDownList(Order::getShippingMethodsLite()) ?>

    <div class="shipping_method_field method_self" <?php if($model->shipping_method != 'self'):?>style="display: none"<?php endif;?>">
        <?= $form->field($model, 'pickup_time')->dropDownList(Yii::$app->params['pickup_time']) ?>
    </div>

    <div class="shipping_method_field address_field" <?php if($model->shipping_method != 'rp' && $model->shipping_method != 'shipping'):?>style="display: none"<?php endif;?>">
        <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
    </div>

    <div class="shipping_method_field city_field" <?php if($model->shipping_method != 'tk'):?>style="display: none"<?php endif;?>">
        <?= $form->field($model, 'city')->textInput(['maxlength' => 255]) ?>
    </div>

    <?= $form->field($model, 'shipping_cost')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'payment_method')->dropDownList(Order::getPaymentMethods()) ?>

    <?= $form->field($model, 'discount')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'shipping_number')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
