<?php
use yii\helpers\Html;
use common\models\ProductPrice;
?>

<hr>

<?= $form->field($model, 'multiprice')->checkbox() ?>

<div class="price-without-count" style="display: none">
    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'new_price')->textInput(['maxlength' => true]) ?>
</div>

<div class="price-and-count">
    <?= Html::a('Добавить', 'javascript:void(0);', [
        'id' => 'product-new-price-button',
        'class' => 'btn btn-default btn-xs'
    ])?>
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
</div>