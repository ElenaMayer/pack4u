<h2>Итого</h2>
<table>
    <?php if(isset($order)):?>
        <?php if($order->shipping_cost):?>
            <tr class="shipping">
                <th>Доставка</th>
                <td>
                    <p><?=$order->shipping_cost?><i class="fa fa-ruble"></i></p>
                </td>
            </tr>
        <?php endif;?>
    <?php else:?>
        <?php if($this->context->action->id == 'order'):?>
            <tr class="order-subtotal">
                <th>Подитог</th>
                <td><strong><span class="amount"><span id="amount_subtotal"><?= $total ?></span><i class="fa fa-ruble"></i></span></strong> </td>
            </tr>
        <?php endif;?>
        <tr class="shipping">
            <th>Доставка</th>
            <td>
                <?php if($this->context->action->id != 'order'):?>
                    <p>Будет рассчитана в зависимости от выбранного способа доставки.</p>
                <?php else:?>
                    <p><span id="amount_shipping">0</span><i class="fa fa-ruble"></i></p>
                <?php endif;?>

            </td>
        </tr>
    <?php endif;?>
    <tr class="order-total">
        <th>Итого</th>
        <td><strong><span class="amount"><span id="amount_total"><?= $total ?></span><i class="fa fa-ruble"></i></span></strong> </td>
    </tr>
    <tr class="min_order_sum" <?php if($total >= Yii::$app->params['orderMinSum']):?>style="display: none"<?php endif;?>>
        <td colspan="2" class="min_sum_error">
            <p>Минимальная сумма заказа <?= Yii::$app->params['orderMinSum']?><i class="fa fa-ruble"></i></p>
        </td>
    </tr>
</table>