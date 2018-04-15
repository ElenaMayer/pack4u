<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use \common\models\Product;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map($categories, 'id', 'title'), ['prompt' => 'Select category']) ?>

    <?= $form->field($model, 'is_in_stock')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <?= $form->field($model, 'is_novelty')->checkbox() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 19]) ?>

    <?= $form->field($model, 'new_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color')->widget(Select2::classname(), [
        'options' => [
            'multiple' => true,
            'placeholder' => Yii::t('app','Выберите цвет ...'),
        ],
        'data'=>$model->getAllColorsArray(),
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators'=>[',',' '],
        ],
    ]) ?>

    <?= $form->field($model, 'tags')->widget(Select2::classname(), [
        'options' => [
            'multiple' => true,
            'placeholder' => Yii::t('app','Выберите теги ...'),
        ],
        'data'=>Product::getTagsArray(),
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators'=>[',',' '],
        ],
    ]) ?>
    <?= $form->field($model, 'relationsArr')->widget(Select2::classname(), [
        'options' => [
            'multiple' => true,
            'placeholder' => Yii::t('app','Выберите связаные товары ...'),
        ],
        'data'=>ArrayHelper::map(Product::find()->all(), 'id', 'article'),
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators'=>[',',' '],
        ],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
