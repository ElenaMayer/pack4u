<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use \common\models\Product;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'imageFiles[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::getCategoryArray(), ['prompt' => 'Выберите категорию ...']) ?>

    <?= $form->field($model, 'subcategories')->widget(DepDrop::classname(), [
        'data'=> Category::getSubcategoryArray($model->category_id),
        'options' => [
            'multiple' => true,
        ],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
            'depends'=>['product-category_id'],
            'url' => Url::to(['/product/get_subcategories']),
            'loadingText' => 'Загрузка ...',
            'tokenSeparators'=>[',',' '],
            'placeholder' => 'Выберите подкатегории ...',
        ],
    ]) ?>

    <?= $form->field($model, 'is_in_stock')->checkbox() ?>

    <?= $form->field($model, 'is_active')->checkbox() ?>

    <?= $form->field($model, 'is_novelty')->checkbox() ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'size')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 19]) ?>

    <?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'new_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sort')->textInput() ?>

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
        'data'=>Product::getProductArr(),
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
