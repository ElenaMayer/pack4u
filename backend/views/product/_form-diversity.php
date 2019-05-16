<?php
use yii\helpers\Html;
?>
<div class="diversity-fields col-lg-3">
    <?php if($diversity->image):?>
        <div class="product-images">
            <?= Html::img($diversity->image->getUrl('small'));?>
        </div>
    <?php endif;?>
    <?= $form->field($diversity, 'imageFile')->fileInput([
        'id' => "ProductDiversity_{$key}_imageFile",
        'name' => "ProductDiversity[$key][imageFile]",
        'accept' => 'image/*'
    ])->label(false) ?>

    <?= $form->field($diversity, 'article')->textInput([
        'maxlength' => true,
        'id' => "ProductDiversity_{$key}_article",
        'name' => "ProductDiversity[$key][article]",
        ]) ?>

    <?= $form->field($diversity, 'title')->textInput([
        'maxlength' => true,
        'id' => "ProductDiversity_{$key}_title",
        'name' => "ProductDiversity[$key][title]",
    ]) ?>

    <?= $form->field($diversity, 'is_active')->checkbox([
        'id' => "ProductDiversity_{$key}_is_active",
        'name' => "ProductDiversity[$key][is_active]",
    ]) ?>

    <?= $form->field($diversity, 'count')->textInput([
        'id' => "ProductDiversity_{$key}_count",
        'name' => "ProductDiversity[$key][count]",
    ])?>

    <?= Html::a('Удалить ', 'javascript:void(0);', [
        'class' => 'product-remove-diversity-button btn btn-default btn-xs',
    ]) ?>
</div>
