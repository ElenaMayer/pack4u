<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductNotification */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->product->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-notification-view">

    <h1><?= Html::encode($model->product->title) ?></h1>
    <div class="product-images">
        <div class="product-image">
            <?= Html::img($model->product->images[0]->getUrl('medium'));?>
        </div>
    </div>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute'=>'user_id',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->user_id){
                        if($model->user->getOrdersCount()){
                            $href = '/order/index?OrderSearch%5Buser_id%5D='.$model->user_id;
                            return '<a href=' . $href . '>' . $model->user->fio . '</a>';
                        } else {
                            return $model->user->fio;
                        }
                    } else {
                        return '';
                    }
                },
            ],
            'created_at',
        ],
    ]) ?>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'is_active')->checkbox(['checked' => 'checked']) ?>

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'comment')->textarea() ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    <?php ActiveForm::end(); ?>
    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверена?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
