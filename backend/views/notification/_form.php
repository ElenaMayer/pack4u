<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Product;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\ProductNotification */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-notification-form">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'is_active')->checkbox(['checked' => 'checked']) ?>
        <div class="col-md-5 col-sm-6">
            <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
                'options' => [
                    'placeholder' => Yii::t('app','Выберите товар ...'),
                ],
                'data' => Product::getProductArr(false),
            ])->label(false) ?>
        </div>
        <div class="col-md-3 col-sm-6">
            <?= $form->field($model, 'diversity_id')->widget(DepDrop::classname(), [
                'data' => [],
                'type' => DepDrop::TYPE_SELECT2,
                'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                'pluginOptions'=>[
                    'depends'=>['productnotification-product_id'],
                    'url' => Url::to(['/order/get_diversity']),
                    'loadingText' => 'Загрузка ...',
                    'tokenSeparators'=>[',',' '],
                    'placeholder' => 'Выберите расцветку ...',
                ],
            ])->label(false) ?>
        </div>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'comment')->textarea() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
