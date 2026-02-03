<?php use \common\models\Order; ?>

<h2>Итого</h2>
<table>
    <tr class="shipping">
        <th>Доставка</th>
        <td>
            <p>Стоимость доставки рассчитывается индивидуально, после оформления заказа</p>
        </td>
    </tr>
    <tr class="order-total">
        <th>Итого</th>
        <!--Самовывозы-->
        <td><strong><span class="amount"><span id="amount_total"><?= $total  ?></span><i class="fa fa-ruble"></i></span></strong> </td>
        <input type="hidden" class="sub-total" value="<?= $total?>">
    </tr>
    <?php if(!isset($order)):?>
        <tr class="min_order_sum" <?php if($total >= Yii::$app->params['orderMinSum']):?>style="display: none"<?php endif;?>>
            <td colspan="2" class="min_sum_error">
                <p>Минимальная сумма заказа <?= Yii::$app->params['orderMinSum']?><i class="fa fa-ruble"></i></p>
            </td>
        </tr>
    <?php endif;?>
    <?php if(Yii::$app->params['importantInfo']):?>
        <tr>
            <td colspan="2" class="important-info">
                <p><?= Yii::$app->params['importantInfo']?></p>
            </td>
        </tr>
    <?php endif;?>
</table>