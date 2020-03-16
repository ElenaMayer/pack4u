<h2>Итого</h2>
<input type="hidden" id="free_shipping_sum" value="<?=Yii::$app->params['freeShippingSum']?>">
<input type="hidden" id="shipping_cost" value="<?=Yii::$app->params['shippingCost']?>">
<input type="hidden" id="sdek_nsk_cost" value="<?=Yii::$app->params['sdekNskCost']?>">
<table>
        <tr class="order-subtotal">
            <th>Подытог</th>
            <td><strong><span class="amount"><span id="amount_subtotal"><?= $subtotal ?></span><i class="fa fa-ruble"></i></span></strong> </td>
        </tr>
        <?php if(isset($discount) && $discount > 0):?>
        <tr class="order-discount red">
            <th>
                Скидка <?= $discountPercent ?>% <i class="fa fa-question-circle"></i>
                <div class="prices-popup hide">
                    <div class="ttip__arrow ttip__arrow_r"></div>
                    <div class="prices-popup-p">
                        <p>Скидка от суммы заказа</p>
                        <p>5 000 руб. - 3%</p>
                        <p>10 000 руб. - 5%</p>
                        <p>15 000 руб. - 7%</p>
                    </div>
                </div>
            </th>
            <td><strong><span class="amount"><span id="amount_discount"><?= $discount ?></span><i class="fa fa-ruble"></i></span></strong> </td>
        </tr>
    <?php endif;?>
    <tr class="shipping">
        <th>Доставка</th>
        <td>
            <?php if($total >= Yii::$app->params['freeShippingSum']):?>
                <p><span id="amount_shipping">БЕСПЛАТНО</span></p>
            <?php elseif($this->context->action->id != 'order' && !isset($order)):?>
                <p>При заказе от <?= Yii::$app->params['freeShippingSum']?><i class="fa fa-ruble"></i> доставка БЕСПЛАТНО</p>
            <?php elseif(isset($order) && $order->shipping_cost):?>
                <p><?=$order->shipping_cost?><i class="fa fa-ruble"></i></p>
            <?php else:?>
                <!--Самовывозы-->
                <p><span id="amount_shipping"><?=Yii::$app->params['sdekNskCost']?><i class="fa fa-ruble"></i></p>
            <?php endif;?>
        </td>
    </tr>
    <tr class="order-total">
        <th>Итого</th>
        <!--Самовывозы-->
        <td><strong><span class="amount"><span id="amount_total"><?= $total + Yii::$app->params['sdekNskCost'] ?></span><i class="fa fa-ruble"></i></span></strong> </td>
    </tr>
    <?php if(!isset($order)):?>
        <tr class="min_order_sum" <?php if($total >= Yii::$app->params['orderMinSum']):?>style="display: none"<?php endif;?>>
            <td colspan="2" class="min_sum_error">
                <p>Минимальная сумма заказа <?= Yii::$app->params['orderMinSum']?><i class="fa fa-ruble"></i></p>
            </td>
        </tr>
    <?php endif;?>
</table>