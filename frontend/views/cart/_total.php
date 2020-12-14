<h2>Итого</h2>
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
            <?php if(isset($shippingCost)):?>
                <?php if($shippingCost == 0):?>
                    <p><span id="amount_shipping">БЕСПЛАТНО</span></p>
                <?php else:?>
                    <div>
                        <?= $shippingCost ?><i class="fa fa-ruble"></i>
                        <div class="shipping_tooltip">
                            <i class="fa fa-question-circle"></i>
                            <span class="tooltip-text">Бесплатная доставка от <?=Yii::$app->params['freeShippingSum']?><i class="fa fa-ruble"></i></span>
                        </div>
                    </div>
                <?php endif;?>
            <?php else:?>
                <p>При заказе от <?= Yii::$app->params['freeShippingSum']?><i class="fa fa-ruble"></i> доставка БЕСПЛАТНО</p>
            <?php endif;?>
        </td>
    </tr>
    <tr class="order-total">
        <th>Итого</th>
        <!--Самовывозы-->
        <td><strong><span class="amount"><span id="amount_total"><?= $total + (isset($shippingCost)?intval($shippingCost):0)  ?></span><i class="fa fa-ruble"></i></span></strong> </td>
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