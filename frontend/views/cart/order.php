<?php
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use \common\models\Order;

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;

$cookies = Yii::$app->request->cookies;
$location = $cookies->getValue('location');
?>

<div class="checkout-wrapper commerce commerce-order">
    <div class="container">
        <?php if (Yii::$app->user->isGuest): ?>
            <?php Yii::$app->user->setReturnUrl(Yii::$app->request->url); ?>
            <div class="text-alert">
                <p>Уже зарегистрированы? <a href="/user/login">Войти</a></p>
            </div><!-- /.text-alert -->
        <?php endif;?>
        <div class="row">
            <div class="col-md-6">
                <h2>Оформление заказа</h2>
                <?php
                /* @var $form ActiveForm */
                $form = ActiveForm::begin([
                    'id' => 'order-form',
                ]);
                $labels = $order->attributeLabels(); ?>
                <?= $form->field($order, 'fio')->textInput(['placeholder' => 'Иванов Иван Иванович', 'class' => 'form-control dark']); ?>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($order, 'email')->textInput(['placeholder' => 'name@mail.ru', 'class' => 'form-control dark']); ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($order, 'phone')->textInput(['placeholder' => '+7900-000-00-00', 'class' => 'form-control dark']); ?>
                    </div>
                </div>
                <?= $form->field($order, 'notes')->textarea(['class' => 'form-control dark', 'rows' => "3"]); ?>

                <?= $form->field($order, 'is_ul')->checkbox() ?>

                <?= $form->field($order, 'payment_method')->radioList(Order::getPaymentMethods(), ['class' => 'radio-list']); ?>

                <?= $form->field($order, 'shipping_method')->dropDownList(Order::getShippingMethod(), ['options' => [$shippingMethod => ['selected' => true]]])->label(false); ?>

                <?= $form->field($order, 'shipping_cost')->hiddenInput(['class' => 'form-control dark'])->label(false); ?>

                <div class="shipping_methods">
                    <div class="self" style="display: none">
                        <?= $form->field($order, 'pickup_time')->dropDownList(Yii::$app->params['pickup_time'], ['prompt'=>'Выберите время...']); ?>
                    </div>
                    <div class="order-address" <?php if($shippingMethod != 'rp'):?>style="display: none"<?php endif;?>>
                        <div class="select_location">Город доставки <a class="link" onclick="$('#geo_city_modal').modal()"><span><?=$location?></span> <i class="fa fa-angle-down"></i></a></div>
                        <?= $form->field($order, 'address')->textInput(['placeholder' => 'ул.Ленина д.1 кв.1', 'class' => 'form-control dark order-address']); ?>
                    </div>
                    <div class="tk" <?php if($shippingMethod != 'tk'):?>style="display: none"<?php endif;?>>
                        <?= $form->field($order, 'city')->textInput(['class' => 'form-control dark', 'disabled' => 'disabled', 'style' => 'display:none;'])->label(false); ?>
                        <div id="forpvz" style="width:100%; height:300px;"></div>
                    </div>
                    <input type="hidden" id="order_weight" value="<?= $order->getWeight() ?>">
                </div>
                <div class="ul_requisites"  style="display: none">
                    <?= $form->field($order, 'ul_requisites')->textInput(['class' => 'form-control dark order-address']); ?>
                </div>

            </div>
            <div class="col-md-6">
                <div class="payment-right">
                    <div class="payment-detail-wrapper">
                        <h2>Ваш заказ</h2>
                        <ul class="cart-list">
                            <?php foreach ($positions as $position):?>
                            <?php $product = $position->getProduct();?>
                                <?php if($product->getIsActive($position->diversity_id)):?>
                                    <?= $this->render('_products', [
                                            'position' => $position,
                                            'product' => $product
                                    ]); ?>
                                <?php endif;?>
                            <?php endforeach ?>
                        </ul> <!-- /.cart-list -->
                    </div><!-- /.payment-detail-wrapper -->
                    <div class="cart_totals">
                        <div id="data_total">
                            <?= $this->render('_total', [
                                'type' => 'order',
                                'subtotal' => $cart->getCost(),
                                'total' => $cart->getCost(true),
                                'discount' => $cart->getDiscount(),
                                'discountPercent' => $cart->getDiscountPercent(),
                                'shippingMethod' => $shippingMethod,
                            ]); ?>
                        </div>

                        <div class="cart-offer">Нажимая кнопку "<span>Оплатить заказ</span>" Вы соглашаетесь с <a href="/offer">Политикой конфиденциальности</a></div>
                        <div class="wc-proceed-to-checkout">
                            <?= Html::submitButton('Оплатить заказ', [
                                'class' => 'checkout-button button alt wc-forward' . ($shippingMethod == 'tk' ? ' disabled' : '')]) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</div><!-- /.checkout-wrapper -->
<script id="ISDEKscript" type="text/javascript" src="https://widget.cdek.ru/widget/widjet.js" charset="utf-8"></script>
<script type="text/javascript">
    var ourWidjet = new ISDEKWidjet ({
        defaultCity: '<?=$location?>', //какой город отображается по умолчанию
        cityFrom: 'Новосибирск', // из какого города будет идти доставка
        country: 'Россия', // можно выбрать страну, для которой отображать список ПВЗ
        link: 'forpvz', // id элемента страницы, в который будет вписан виджет
        path: 'https://widget.cdek.ru/widget/scripts/', //директория с библиотеками
        servicepath: 'http://<?=Yii::$app->params['domain']?>/service.php', //ссылка на файл service.php на вашем сайте
        hidedress: true,
        hidecash: true,
        // hidedelt: true,
        region: true,
        detailAddress: true,
        goods: [ // установим данные о товарах из корзины
            { length : 25, width : 35, height : 5, weight : <?= $order->getWeight() ?> }
        ],
        onReady: function(){ // на загрузку виджета отобразим информацию о доставке до ПВЗ
            ipjq('#linkForWidjet').css('display','inline');
        },
        onChoose: function(info){ // при выборе ПВЗ: запишем информацию в текстовые поля
            $('#order-city').val(info.id + ', ' + info.cityName + ', ' + info.PVZ.Address).show();
            subtotal = parseInt($('#amount_subtotal').html());
            shipping = parseInt(info.price);
            if(shipping) {
                $('#order-shipping_cost').val(shipping);
                $('tr.shipping span.amount').html(shipping);
                $('#amount_total').html(subtotal + shipping);
                $('.amount_sum').show();
                $('.amount_hint').hide();
                $('.checkout-button').removeClass('disabled');
            }
            $('.CDEK-widget__panel').removeClass('open');
            $('.CDEK-widget__sidebar-burger').addClass('close').removeClass('active','open');
        },
        onChooseProfile: function(info){
            subtotal = parseInt($('#amount_subtotal').html());
            shipping = parseInt(info.price);
            $('#order-shipping_cost').val(shipping);
            $('tr.shipping span.amount').html(shipping);
            $('#amount_total').html(subtotal+shipping);
            $('.amount_sum').show();
            $('.amount_hint').hide();
            $('.checkout-button').removeClass('disabled');
            $('#order-city').hide();
        },
        onChooseAddress: function(info){
            $('#order-city').val(info.address).show();
        },
        onCalculate:function(info){
            subtotal = parseInt($('#amount_subtotal').html());
            shipping = parseInt(info.price);
            if(shipping) {
                $('#order-shipping_cost').val(shipping);
                $('tr.shipping span.amount').html(shipping);
                $('#amount_total').html(subtotal + shipping);
                $('.amount_sum').show();
                $('.amount_hint').hide();
                $('.checkout-button').removeClass('disabled');
            } else {
                $('.amount_sum').hide();
                $('.amount_hint').show();
                $('.checkout-button').addClass('disabled');
            }
        }
    });
</script>