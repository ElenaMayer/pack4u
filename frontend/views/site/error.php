<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Ошибка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="pt-5 pb-10">
    <div class="container">
        <div class="row about">
            <h1><?= Html::encode($name) ?></h1>

            <div class="alert alert-danger">
                <?= nl2br(Html::encode($message)) ?>
            </div>
        </div>
    </div>
</div>

