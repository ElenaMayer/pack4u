<?php
use yii\helpers\Html;
?>
<td>
    <?= $form->field($price, 'price')->textInput([
        'id' => "ProductPrice_{$key}_price",
        'name' => "ProductPrice[$key][price]",
    ])->label(false) ?>
</td>
<td>
    <?= $form->field($price, 'count')->textInput([
        'id' => "ProductPrice_{$key}_count",
        'name' => "ProductPrice[$key][count]",
    ])->label(false) ?>
</td>
<td>
    <?= Html::a('Удалить ', 'javascript:void(0);', [
        'class' => 'product-remove-price-button btn btn-default btn-xs',
    ]) ?>
</td>