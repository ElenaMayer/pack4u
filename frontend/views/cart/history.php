<?php
use common\models\Order;

$this->title = 'История заказов';
$this->params['breadcrumbs'][] = $this->title;

?>
<?php if(count($history) > 0):?>
<div class="commerce commerce-page commerce-cart noo-shop-main history">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-12">
                <table class="shop_table cart">
                    <thead>
                    <tr>
                        <th class="order-id">Заказ</th>
                        <th class="order-status">Статус</th>
                        <th class="order-total">Сумма</th>
                        <th class="order-date">Дата создания</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($history as $order):?>
                        <tr class="cart_item">
                            <td class="order-id">
                                <a href="/history/<?= $order->id ?>"><?=  $order->id?></a>
                            </td>
                            <td class="order-status">
                                <?=  Order::getStatuses()[$order->status]?>
                            </td>
                            <td class="order-total">
                                <span class="amount"><?=  $order->getCost()?><i class="fa fa-ruble"></i></span>
                            </td>
                            <td class="order-date">
                                <?=  Yii::$app->formatter->asDate($order->created_at);?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="commerce commerce-cart noo-shop-main">
    <div class="container">
        <div class="row">
            <div class="noo-main col-md-12">
                <p class="cart-empty">
                    У Вас еще нет заказов.
                </p>
                <p class="return-to-shop">
                    <a class="button wc-backward" href="/catalog">
                        Вернуться к покупкам
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php endif;?>
<div class="noo-footer-shop-now">
    <div class="container">
        <div class="col-md-7">
            <h4>- Стильная упаковка -</h4>
            <h3>ДЛЯ ВАС</h3>
        </div>
    </div>
</div>
