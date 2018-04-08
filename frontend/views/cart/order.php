<?php
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use \common\models\Order;

/* @var $this yii\web\View */
/* @var $products common\models\Product[] */

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="checkout-wrapper">
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

                <?php echo $form->field($order, 'payment_method')->dropDownList(Order::getPaymentMethods()); ?>
                <?php echo $form->field($order, 'shipping_method')->dropDownList(Order::getShippingMethods()); ?>

                <div class="shipping_methods">
                    <div class="rp" style="display: none">
                        <?= $form->field($order, 'address')->textInput(['placeholder' => '630000, Новосибирск, ул.Ленина д.1 кв.1', 'class' => 'form-control dark']); ?>
                    </div>
                    <div class="tk" style="display: none">
                        <?= $form->field($order, 'city')->textInput(['class' => 'form-control dark']); ?>
                        <?= $form->field($order, 'tk')->dropDownList(Order::getTkList()); ?>
                    </div>
                    <div class="rcr" style="display: none">
                        <?= $form->field($order, 'rcr')->textInput(['placeholder' => 'РЦР Маркса', 'class' => 'form-control dark']); ?>
                    </div>
                </div>

                <?php if (Yii::$app->user->isGuest): ?>
                    <!--            <div class="checkbox">-->
                    <!--                <label>-->
                    <!--                    <input type="checkbox" value="">-->
                    <!--                    <span>Создать аккаунт?</span>-->
                    <!--                </label>-->
                    <!--            </div><!-- /.checkbox -->
                <?php endif;?>
            </div>
            <div class="col-md-6">
                <div class="payment-right">
                    <h2>Ваш заказ</h2>
                    <div class="payment-detail-wrapper">
                        <ul class="cart-list">
                            <?php foreach ($products as $product):?>
                                <?php if($product->getIsActive() && $product->getIsInStock()):?>
                                    <?= $this->render('_products', ['product'=>$product]); ?>
                                <?php endif;?>
                            <?php endforeach ?>
                        </ul> <!-- /.cart-list -->
                    </div><!-- /.payment-detail-wrapper -->
                    <div class="cart_totals">
                        <?= $this->render('_total', ['total'=>$total]); ?>

<!--                        <div class="cart-offer">Нажимая на кнопку "Отправить заказ",</br> вы принимаете условия --><?//= Html::a('Публичной оферты', ['site/offer'])?><!--</div>-->
                        <div class="wc-proceed-to-checkout">
                            <?= Html::submitButton('Отправить заказ', ['class' => 'checkout-button button alt wc-forward']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div><!-- /.container -->
</div><!-- /.checkout-wrapper -->