<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\ProductDiversity;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-notification-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать уведомление', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'format' => 'html',
                'value'=>function($model) {
                    if($model->diversity_id) {
                        $div = ProductDiversity::findOne($model->diversity_id);
                    }
                    if($div && $div->image){
                        $href = '/product/view?id=' . $model->product_id;
                        return '<a href="' . $href . '"><img src="' . $div->image->getUrl('small') . '"></a>';
                    } elseif(isset($model->product->images[0])) {
                        $href = '/product/view?id=' . $model->product_id;
                        return '<a href="' . $href . '"><img src="' . $model->product->images[0]->getUrl('small') . '"></a>';
                    } else
                        return '';
                }
            ],
            [
                'attribute'=>'is_active',
                'value' => function ($model) {
                    return $model->is_active ? 'Да' : 'Нет';
                },
                'filter' => [1 => 'Да', 0 => 'Нет']
            ],
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
            'phone',
            'comment',
            'created_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]); ?>


</div>
