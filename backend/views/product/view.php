<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use common\models\Category;

/* @var $this yii\web\View */
/* @var $model common\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">
    <h1>
        <a href='http://<?= Yii::$app->params['domain']?>/catalog/<?=$model->category->slug?>/<?=$model->id?>'>
            <?= Html::encode($this->title) ?>
        </a>
        <?php if(!$model->is_active):?>
            <span class="not_active">Не отображается</span>
                <?php elseif(!$model->diversity && !$model->is_in_stock):?>
            <span class="not_is_stock">Закончилось</span>
        <?php endif;?>
    </h1>
    <div class="product-images">
        <?php foreach ($model->images as $image):?>
            <div class="product-image">
                <?= Html::a('x', ['/image/delete', 'id' => $image->id], ['class' => 'image_remove']) ?>
                <?= Html::img($image->getUrl('small'));?>
            </div>
        <?php endforeach;?>
    </div>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Копировать', ['copy', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
        <?= Html::a('История изменений', ['/history/index', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('История заказов', ['/history/order', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Уверена? Я бы не стала этого делать :)',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'article',
            'title',
            'description:ntext',
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return empty($model->category_id) ? '-' : $model->category->title;
                },
            ],
            [
                'attribute' => 'subcategories',
                'value' => function ($model) {
                    $subcatArr = [];
                    foreach (explode(",",$model->subcategories) as $category_id){
                        $category = Category::findOne($category_id);
                        if($category)
                            $subcatArr[] = $category->title;
                    }
                    return implode(",", $subcatArr);
                },
            ],
            [
                'attribute'=>'price',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->multiprice) {
                        return $model->getMultipricesStrFull();
                    } elseif($model->new_price) {
                        return '<s>' . $model->price . '</s> ' . $model->new_price;
                    } else {
                        return $model->price;
                    }
                },
            ],
            'size',
            'color',
            [
                'attribute'=>'count',
                'format' => 'html',
                'value' => function ($model) {
                    if($model->diversity) {
                        $result = '';
                        foreach ($model->diversities as $diversity){
                            $result .= $diversity->title . ' - ' . $diversity->count . 'шт <br>';
                        }
                    return $result;
                    } else
                        return $model->count;
                },
            ],
            'weight',
            'tags',
            'is_active',
            'is_in_stock',
            'is_novelty',
            'instruction',
            'sort',
            'time'
        ],
    ]) ?>

    <?php if($model->diversity && $model->diversities):?>
        <h2>Расцветка</h2>

        <table class="table table-striped table-bordered">
            <tr>
                <th></th><th>Артикул</th><th>Название</th><th>Количество</th><th>Показывать</th>
            </tr>
            <?php foreach ($model->diversities as $diversity): ?>
                <tr>
                    <td>
                        <?php if($diversity->image_id && $diversity->image):?>
                            <div>
                                <a href='http://<?= Yii::$app->params['domain']?>/catalog/<?=$model->category->slug?>/<?=$model->id?>/<?=$diversity->id?>'>
                                    <?= Html::img($diversity->image->getUrl('small'));?>
                                </a>
                            </div>
                        <?php endif;?>
                    </td>
                    <td>
                        <?= $diversity->article;?>
                    </td>
                    <td>
                        <?= $diversity->title;?>
                    </td>
                    <td>
                        <?= $diversity->count;?>
                    </td>
                    <td>
                        <?= ($diversity->is_active)? 'Да' : 'Нет' ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </table>

    <?php endif;?>

    <?php if($model->relations):?>
        <h2><?= $model->getAttributeLabel('relationsArr'); ?></h2>
        <div class="product-images">
            <?php foreach ($model->relations as $relation):?>
                <?php if($relation->child->images):?>
                    <div class="product-image">
                        <a href="<?= Url::toRoute(['product/view', 'id' => $relation->child_id])?>">
                            <?= Html::img($relation->child->images[0]->getUrl('small'));?>
                        </a>
                    </div>
                <?php endif;?>
            <?php endforeach;?>
        </div>
    <?php endif;?>
</div>
