<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use \common\models\Product;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use common\models\Category;
use common\models\ProductPrice;

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

    <?= $form->field($model, 'count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

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

    <?= $form->field($model, 'instruction')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

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

    <?= $form->field($model, 'multiprice')->checkbox() ?>

    <div class="price-without-count" style="display: none">

        <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'new_price')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="price-and-count">
        <fieldset>
            <legend>
                Цены
                <?= Html::a('Добавить', 'javascript:void(0);', [
                    'id' => 'product-new-price-button',
                    'class' => 'pull-right btn btn-default btn-xs'
                ])?>
            </legend>
            <?php
            $price = new ProductPrice();
            $price->loadDefaultValues(); ?>

            <table id="product-prices" class="table table-condensed table-prices">
                <thead>
                <tr>
                    <th><?=$price->getAttributeLabel('price')?></th>
                    <th><?=$price->getAttributeLabel('count')?></th>
                    <td>&nbsp;</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->prices as $key => $_price):?>
                    <tr>
                        <?= $this->render('_form-price', [
                            'key' => $_price->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_price->id,
                            'form' => $form,
                            'price' => $_price,
                        ]);?>
                    </tr>
                <?php endforeach;?>

                <tr id="product-new-price-block" style="display: none;">
                    <?= $this->render('_form-price', [
                        'key' => '__id__',
                        'form' => $form,
                        'price' => $price,
                    ]);?>
                </tr>
                </tbody>
            </table>

            <?php ob_start(); // output buffer the javascript to register later ?>
            <script>
                var price_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
                $('#product-new-price-button').on('click', function () {
                    price_k += 1;
                    $('#product-prices').find('tbody')
                        .append('<tr>' + $('#product-new-price-block').html().replace(/__id__/g, 'new' + price_k) + '</tr>');
                });
                $(document).on('click', '.product-remove-price-button', function () {
                    $(this).closest('tbody tr').remove();
                });
                <?php
                // OPTIONAL: click add when the form first loads to display the first new row
                if (!Yii::$app->request->isPost && empty($model->prices))
                    echo "$('#product-new-price-button').click();";
                ?>
            </script>
            <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>

        </fieldset>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Редактировать', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
