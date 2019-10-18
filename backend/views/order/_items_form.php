<?php
use common\models\OrderItem;
use common\models\Product;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use common\models\ProductDiversity;
?>


<a class="btn btn-success add-item-link">Добавить позицию</a><h2>Заказ:</h2>
<div class="add-item-form hide">
    <?php $form = ActiveForm::begin();
    $orderItem = new OrderItem(); ?>

    <?= $form->field($orderItem, 'product_id')->widget(Select2::classname(), [
        'options' => [
            'placeholder' => Yii::t('app','Выберите товар ...'),
        ],
        'data'=>Product::getProductArr(false),
    ]) ?>

    <?= $form->field($orderItem, 'diversity_id')->widget(DepDrop::classname(), [
        'data'=> [],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
        'pluginOptions'=>[
            'depends'=>['orderitem-product_id'],
            'url' => Url::to(['/order/get_diversity']),
            'loadingText' => 'Загрузка ...',
            'tokenSeparators'=>[',',' '],
            'placeholder' => 'Выберите расцветку ...',
        ],
    ]) ?>

    <?= $form->field($orderItem, 'quantity')->textInput(['step' => 1, 'min' => 1, 'value' => 1]) ?>
    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<table class="table table-striped table-bordered detail-view">
    <tr>
        <th>Фото</th><th>Товар</th><th>Цена</th><th>Количество</th><th>Всего</th><th></th>
    </tr>
    <?php
    if($model->orderItems):
        foreach ($model->getSortOrderItems() as $item): ?>
            <tr>
                <td>
                    <div class="product-image">
                        <?php if($item->diversity_id):?>
                            <a href="/product/view?id=<?= $item->product->id?>">
                                <?php $div = ProductDiversity::findOne($item->diversity_id);?>
                                <?= Html::img($div->image->getUrl('small'));?>
                            </a>
                        <?php elseif($item->product->images):?>
                            <a href="/product/view?id=<?= $item->product->id?>">
                                <?= Html::img($item->product->images[0]->getUrl('small'));?>
                            </a>
                        <?php endif;?>
                    </div>
                </td>
                <td>
                    <?php echo $item->title . ' ' . ($item->product_id ? $item->product->size . 'см (Арт. '. $item->product->article .')' : '');?>
                </td>
                <td>
                    <input type="text" value="<?= (int)$item->price?>" class="cartitem_price_value m_input">
                    <?= Html::button('Ок', [
                        'class' => 'btn btn-success update_price',
                    ]) ?>
                </td>
                <td>
                    <input type="text" value="<?= $item->quantity?>" class="cartitem_qty_value m_input">
                    <input type="hidden" value="<?= $item->id?>" class="cartitem_id">
                    <?= Html::button('Ок', [
                        'class' => 'btn btn-success update_qty',
                    ]) ?>
                </td>
                <td>
                    <?= (int)($item->price * $item->quantity) . ' руб.'?>
                </td>
                <td>
                    <?= Html::a('x', ['delete_item', 'id' => $item->id], [
                        'class' => 'btn btn-danger',
                    ]) ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <td>
                <p><string>Вес: </string> <?= $model->getWeight()?> кг</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><string>Итого: </string> <?= $model->getSubCost()?> руб.</p>
            </td>
        </tr>
        <?php if($model->discount):?>
        <tr>
            <td>
                <p><string>Скидка: </string> <?= $model->discount?>%</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><string>Итого со скидкой: </string> <?= $model->getCostWithDiscount() ?> руб.</p>
            </td>
        </tr>
    <?php endif;?>
        <?php if($model->shipping_cost):?>
        <tr>
            <td>
                <p><string>Доставка: </string> <?= $model->shipping_cost?> руб.</p>
            </td>
        </tr>
        <tr>
            <td>
                <p><string>К оплате: </string> <?= $model->getCost()?> руб.</p>
            </td>
        </tr>
    <?php endif;?>
    <?php endif;?>
</table>