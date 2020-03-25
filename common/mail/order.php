<?php
/* @var $order common\models\Order */
use yii\helpers\Html;
use common\models\Order;
use common\models\ProductDiversity;
?>

<h1>Заказ #<?= $order->id ?> успешно создан.</h1>

<ul style="list-style: none;">
    <?php if($order->is_ul):?>
        <li><b>Юридическое лицо</b></li>
    <?php endif;?>
    <li><b>ФИО:</b> <?= Html::encode($order->fio) ?></li>
    <li><b>Телефон:</b> <?= Html::encode($order->phone) ?></li>
    <?php if($order->email):?>
        <li><b>Email:</b> <?= Html::encode($order->email) ?></li>
    <?php endif;?>
    <?php if($order->shipping_method):?>
        <li><b>Доставка:</b> <?php if($order->shipping_method != 'shipping'):?><?= Order::getShippingMethodsLite()[$order->shipping_method]; ?><?php endif;?></li>
    <?php endif;?>
    <?php if($order->shipping_method == 'tk'):?>
        <?php if($order->city):?>
            <li><b>Город:</b> <?= $order->city ?></li>
        <?php endif;?>
    <?php endif;?>
    <?php if($order->shipping_method == 'rp' || $order->shipping_method == 'shipping'):?>
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
                    <?php if($item->diversity_id):?>
                        <?php $div = ProductDiversity::findOne($item->diversity_id);
                        if($div):?>
                            <a href="<?= Yii::$app->params['domain']; ?>/catalog/<?= $item->product->category->slug ?>/<?= $item->product_id ?>/<?=$item->diversity_id?>">
                                <?= Html::img($div->image->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$item->title]);?>
                            </a>
                        <?php endif;?>
                    <?php else:?>
                        <a href="<?= Yii::$app->params['domain']; ?>/catalog/<?= $item->product->category->slug ?>/<?= $item->product_id?>">
                            <?= Html::img($item->product->images[0]->getUrl('small'), ['width' => '100', 'height' => '100', 'alt'=>$item->title]);?>
                        </a>
                    <?php endif;?>
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
            <p><b>Подытог: </b> <?= $order->getSubCost(); ?> р</p>
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

