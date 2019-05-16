<?php
use yii\helpers\Html;
use common\models\ProductDiversity;
?>
<hr>

<?= $form->field($model, 'diversity')->checkbox() ?>

<div class="diversity">
    <?= Html::a('Добавить', 'javascript:void(0);', [
        'id' => 'product-new-diversity-button',
        'class' => 'btn btn-default btn-xs'
    ])?>
    <?php
    $diversity = new ProductDiversity();
    $diversity->loadDefaultValues(); ?>

    <div id="product-diversities">
        <?php foreach ($model->diversities as $key => $_diversity):?>
            <?= $this->render('_form-diversity', [
                'key' => $_diversity->isNewRecord ? (strpos($key, 'new') !== false ? $key : 'new' . $key) : $_diversity->id,
                'form' => $form,
                'diversity' => $_diversity,
            ]);?>
        <?php endforeach;?>

        <div id="product-new-diversity-block" style="display: none;">
            <?= $this->render('_form-diversity', [
                'key' => '__id__',
                'form' => $form,
                'diversity' => $diversity,
            ]);?>
        </div>
    </div>

    <?php ob_start(); // output buffer the javascript to register later ?>
    <script>
        var diversity_k = <?php echo isset($key) ? str_replace('new', '', $key) : 0; ?>;
        $('#product-new-diversity-button').on('click', function () {
            diversity_k += 1;
            $('#product-diversities')
                .append($('#product-new-diversity-block').html().replace(/__id__/g, 'new' + diversity_k));
        });
        $(document).on('click', '.product-remove-diversity-button', function () {
            $(this).closest('div').remove();
        });
        <?php
        // OPTIONAL: click add when the form first loads to display the first new row
        if (!Yii::$app->request->isPost && empty($model->diversities))
            echo "$('#product-new-diversity-button').click();";
        ?>
    </script>
    <?php $this->registerJs(str_replace(['<script>', '</script>'], '', ob_get_clean())); ?>
</div>