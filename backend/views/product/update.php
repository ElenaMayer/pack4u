<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = 'Редактирование: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="product-images">
        <?php foreach ($model->images as $image):?>
            <?= Html::img($image->getUrl('small'));?>
        <?php endforeach;?>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
