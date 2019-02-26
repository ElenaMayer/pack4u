<?php
/* @var $order common\models\Order */
use yii\helpers\Html;
use common\models\Order;
?>

<h1>Заказ #<?= $order->id ?> успешно создан.</h1>

<ul style="list-style: none;">
    <li><b>ФИО:</b> <?= Html::encode($order->fio) ?></li>
    <li><b>Телефон:</b> <?= Html::encode($order->phone) ?></li>
    <?php if($order->email):?>
        <li><b>Email:</b> <?= Html::encode($order->email) ?></li>
    <?php endif;?>
    <?php if($order->shipping_method):?>
        <li><b>Доставка:</b> <?= Order::getShippingMethods()[$order->shipping_method]; ?></li>
    <?php endif;?>
    <?php if($order->shipping_method == 'tk' && $order->tk):?>
        <?php if($order->city):?>
            <li><b>Город:</b> <?= $order->city ?></li>
        <?php endif;?>
        <li><b>ТК:</b> <?= Order::getTkList()[$order->tk]; ?></li>
    <?php endif;?>
    <?php if($order->shipping_method == 'rcr' && $order->rcr):?>
        <li><b>РЦР:</b> <?= $order->rcr?></li>
    <?php endif;?>
    <?php if($order->shipping_method == 'rp'):?>
        <?php if($order->zip):?>
            <li><b>Индекс:</b> <?= $order->zip ?></li>
        <?php endif;?>
        <?php if($order->address):?>
            <li><b>Адрес:</b> <?= $order->address ?></li>
        <?php endif;?>
    <?php endif;?>
    <?php if($order->payment_method):?>
        <li><b>Оплата:</b> <?= Order::getPaymentMethods()[$order->payment_method]; ?></li>
    <?php endif;?>
</ul>

<?php if($order->notes):?>
    <h2>Комментарий</h2>
    <?= Html::encode($order->notes) ?>
<?php endif;?>

<h2>Заказ:</h2>
<table class="table table-striped table-bordered detail-view">
    <tr>
        <th></th><th>Название</th><th>Цена</th><th>Кол-во</th><th>Всего</th>
    </tr>
    <?php foreach ($order->orderItems as $item): ?>
        <tr>
            <td>
                <div class="product-image">
                    <a href="<?= Yii::$app->params['domain']; ?>/catalog/<?= $item->product->category->slug?>/<?= $item->product->id?>">
                        <?= Html::img($item->product->images[0]->getUrl('small'));?>
                    </a>
                </div>
            </td>
            <td>
                <?= $item->title . ' ' . $item->product->size . 'см  (Арт. '. $item->product->article .')'?>
            </td>
            <td>
                <?= (int)$item->price?> р.
            </td>
            <td>
                <?= $item->quantity?>
            </td>
            <td>
                <?= $item->getCost()?> р.
            </td>
        </tr>
    <?php endforeach ?>
    <tr>
        <td>
            <p><b>Вес: </b> <?= $order->getWeight()?> кг</p>
        </td>
    </tr>
    <?php if($order->shipping_cost):?>
    <tr>
        <td>
            <p><b>Подитог: </b> <?= $order->getSubCost(); ?> р</p>
        </td>
    </tr>
    <tr>
        <td>
            <p><b>Доставка: </b> <?= $order->shipping_cost?> р</p>
        </td>
    </tr>
    <?php endif;?>
    <tr>
        <td>
            <p><b>Итого: </b> <?= $order->getCost(); ?> р</p>
        </td>
    </tr>
</table>

