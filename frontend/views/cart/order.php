<?php
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use \common\models\Order;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;

$cookies = Yii::$app->request->cookies;
$location = $cookies->getValue('location');
$zipcode = $cookies->getValue('zipcode');
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

                <div class="ul_requisites"  style="display: none">
                    <?= $form->field($order, 'ul_requisites')->textInput(['class' => 'form-control dark order-address']); ?>
                </div>

                <div class="select_location">Город доставки <a class="link" onclick="$('#geo_city_modal').modal()"><span><?=$location?></span> <i class="fa fa-angle-down"></i></a></div>

                <?= $form->field($order, 'shipping_method')->dropDownList(Order::getShippingMethod(), ['options' => [$shippingMethod => ['selected' => true]]])->label(false); ?>

                <?= $form->field($order, 'shipping_cost')->hiddenInput(['class' => 'form-control dark'])->label(false); ?>

                <div class="shipping_methods">
                    <div class="self" style="display: none">
                        <?= $form->field($order, 'pickup_time')->dropDownList(Yii::$app->params['pickup_time'], ['prompt'=>'Выберите время...']); ?>
                    </div>
                    <div class="order-address" style="display: none">
                        <?= $form->field($order, 'address')->textInput(['placeholder' => 'ул.Ленина д.1 кв.1', 'class' => 'form-control dark order-address']); ?>
                    </div>
                    <div class="tk">
                        <?php if($zipcode == 0):?>
                            <?= $form->field($order, 'city')->textInput(['class' => 'form-control dark'])->label('Пункт выдачи'); ?>
                        <?php else:?>
                            <?= $form->field($order, 'city')->label('Пункт выдачи')->widget(Select2::classname(), [
                                'options' => [
                                    'multiple' => false,
                                    'placeholder' => 'Выберите пункт выдачи ...',
                                ],
                                'data' => Yii::$app->cdek->getPvz($zipcode),
                            ]) ?>
                        <?php endif;?>
                    </div>
                    <input type="hidden" id="order_weight" value="<?= $order->getWeight() ?>">
                </div>

                <?= $form->field($order, 'payment_method')->radioList(Order::getPaymentMethods(), ['class' => 'radio-list']); ?>
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
                                'shippingCost' => Order::getShippingCost('tk'),
                            ]); ?>
                        </div>

                        <div class="cart-offer">Нажимая кнопку "<span>Оплатить заказ</span>" Вы соглашаетесь с <a href="/offer">Политикой конфиденциальности</a></div>
                        <div class="wc-proceed-to-checkout">
                            <?= Html::submitButton('Оплатить заказ', [
                                'class' => 'checkout-button button alt wc-forward']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</div><!-- /.checkout-wrapper -->