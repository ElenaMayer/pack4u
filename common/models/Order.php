<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $phone
 * @property string $address
 * @property string $email
 * @property string $notes
 * @property string $status
 * @property string $fio
 * @property integer $shipping_cost
 * @property string $payment
 * @property string $city
 * @property string $shipping_method
 * @property string $payment_method
 * @property integer $zip
 * @property string $tk
 * @property string $rcr
 * @property string $pickup_time
 * @property integer $discount
 * @property string $payment_id
 * @property string $payment_url
 * @property string $payment_error
 * @property string $shipping_number
 * @property integer $is_ul
 * @property string $ul_requisites
 *
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'x_new';
    const STATUS_COLLECTED = 'was_collected';
    const STATUS_PACKED = 'was_collect';
    const STATUS_SHIPPED = 'in_shipping';
    const STATUS_DONE = 'done';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAYMENT = 'payment';
    const STATUS_PRE_ORDER = 'is_pre_order';
    const STATUS_PAID = 'was_paid';

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'shipping_cost', 'zip', 'discount', 'is_ul'], 'integer'],
            [['address', 'notes'], 'string'],
            [['phone', 'email', 'status', 'fio', 'city', 'shipping_method', 'payment_method', 'tk', 'rcr', 'pickup_time',
                'payment', 'payment_id', 'payment_url', 'payment_error', 'shipping_number', 'ul_requisites'], 'string', 'max' => 255],
            [['phone', 'fio'], 'required'],
            [['email'], 'trim'],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'phone' => 'Телефон',
            'address' => 'Адрес',
            'email' => 'Email',
            'notes' => 'Комментарий',
            'status' => 'Статус',
            'fio' => 'ФИО',
            'shipping_cost' => 'Стоимость доставки',
            'payment' => 'Оплата',
            'city' => 'Город',
            'shipping_method' => 'Способ доставки',
            'payment_method' => 'Способ оплаты',
            'zip' => 'Индекс',
            'tk' => 'Транспортная компания',
            'rcr' => 'Пункт выдачи РЦР',
            'pickup_time' => 'Время получения',
            'discount' => 'Скидка',
            'payment_error' => 'Ошибка оплаты',
            'shipping_number' => 'Трэк/накладная',
            'is_ul' => 'Юридическое лицо',
            'payment_url' => 'Ссылка на оплату',
            'ul_requisites' => 'Наименование организации и ИНН'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->status = self::STATUS_NEW;
            } else {
                $oldAttributes = $this->getOldAttributes();
                if($this->status != $oldAttributes['status']) {
                    if($this->status != $oldAttributes['status']) {
                        if($this->status == self::STATUS_CANCELED) {

                            Yii::debug('Отменен заказ #' . $this->id . ' ->', 'order');

                            foreach ($this->orderItems as $item){

                                if(!$item->diversity_id){
                                    Yii::debug( 'Арт.' . $item->product->article . ' ' . $item->product->count . ' -> ' . ($item->product->count+$item->quantity) . 'шт', 'order');
                                } else {
                                    Yii::debug('Расцветка Арт.' . $item->diversity->article . ' ' . $item->diversity->count . ' -> ' . ($item->diversity->count+$item->quantity) . 'шт', 'order');
                                }

                                $item->product->plusCount($item->quantity, $item->diversity_id);
                            }
                        } elseif($oldAttributes['status'] == self::STATUS_CANCELED){

                            Yii::debug('Открыт отмененный заказ #' . $this->id . ' ->', 'order');

                            foreach ($this->orderItems as $item){

                                if(!$item->diversity_id){
                                    Yii::debug( 'Арт.' . $item->product->article . ' ' . $item->product->count . ' -> ' . ($item->product->count-$item->quantity) . 'шт', 'order');
                                } else {
                                    Yii::debug('Расцветка Арт.' . $item->diversity->article . ' ' . $item->diversity->count . ' -> ' . ($item->diversity->count-$item->quantity) . 'шт', 'order');
                                }

                                $item->product->minusCount($item->quantity, $item->diversity_id);
                            }
                        }
                    }
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public static function getStatuses()
    {
        return [
            self::STATUS_NEW => 'Новый',
            self::STATUS_PAID => 'Оплачено',
            self::STATUS_COLLECTED => 'Собран',
            self::STATUS_PACKED => 'Упакован',
            self::STATUS_PAYMENT => 'Платеж отменен',
            self::STATUS_SHIPPED => 'Передан в доставку',
            self::STATUS_PRE_ORDER => 'Предзаказ',
            self::STATUS_DONE => 'Выполнен',
            self::STATUS_CANCELED => 'Отменен',
        ];
    }

    public static function getShippingMethods()
    {
        return [
            'tk' => 'ТК СДЭК',
            'rp' => 'Почта России',
        ];
    }

    //Самовывозы
    public static function getShippingMethodsFree()
    {
        return [
            'shipping' => 'Доставка',
        ];
    }

    public static function getShippingMethodsNsk()
    {
        return [
            //'self' => "Самовывоз (" . Yii::$app->params['address'] . ")",
            'tk' => 'ТК СДЭК',
            //'courier' => 'Курьер до адреса',
        ];
    }

    public static function getShippingMethodsZone1(){
        return [
            'tk' => 'ТК СДЭК',
        ];
    }

    public static function getShippingMethodsLite()
    {
        return [
            'tk' => 'СДЭК',
            'rp' => 'Почта',
            'shipping' => 'Доставка',
            'courier' => 'Курьер',
            'nrg' => 'Энегрия',
            'self' => 'Самовывоз',
        ];
    }

    //Самовывозы
    public static function getPaymentMethods()
    {
        return [
            'account' => 'Оплата по счету',
            //'cash' => 'Наличными при получении',
            'online' => 'Банковской картой онлайн (комиссия 0%)',
            'card' => 'Переводом на карту',
        ];
    }

    public static function getPaymentTypes()
    {
        return [
            'pending' => 'Платеж создан',
            'succeeded' => 'Оплачено с сайта',
            'canceled' => 'Платеж отменен',
        ];
    }

    public static function getTkList()
    {
        return [
            'cdek' => 'СДЭК',
            'nrg' => 'Энергия',
            //'dellin' => 'Деловые линии',
        ];
    }

    public function sendOrderEmail()
    {
        $emails = [Yii::$app->params['adminEmail']];
        if($this->email)
            $emails[] = $this->email;
        StaticFunction::sendEmail(
            $this,
            'order',
            $emails,
            'Заказ #' . $this->id . ' создан.');
    }

    public function getSubCost()
    {
        $cost = 0;
        foreach ($this->orderItems as $item) {
            $cost += $item->getCost();
        }
        return $cost;
    }

    public function getCost()
    {
        $cost = $this->getCostWithDiscount();
        return $cost + $this->shipping_cost;
    }

    public function getCostWithDiscount()
    {
        $cost = $this->getSubCost();
        if($this->discount > 0)
            return $cost - $cost * $this->discount/ 100;
        else
            return $cost;
    }

    public function getDiscountValue()
    {
        $cost = $this->getSubCost();
        if($this->discount > 0)
            return $cost * $this->discount/ 100;
        else
            return 0;
    }
    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach ($this->orderItems as $item) {
            $product = Product::findOne($item->product_id);
            $product->plusCount($item->quantity, $item->diversity_id);
        }

        return true;
    }

    public function getWeight(){
        $weight = 0;
        if($this->orderItems){
            foreach ($this->orderItems as $item) {
                $weight += $item->getWeight();
            }
        } else {
            $cart = \Yii::$app->cart;
            $positions = $cart->getPositions();
            foreach ($positions as $position) {
                $product = $position->getProduct();
                $weight += $product->weight * $position->getQuantity();
            }
        }
        return $weight * 1.1;
    }

    public function getSortOrderItems(){
        return OrderItem::find()
            ->joinWith('product')
            ->where(['order_id' => $this->id])
            ->orderBy('product.article')
            ->all();
    }

    public static function isSameFioExist($fio){
        $orders = Order::find()
            ->where(['fio' => $fio])
            ->all();
        if(count($orders) > 1)
            return true;
        else
            return false;
    }

    public function checkPayment(){
        $payment = new Payment();
        $res = $payment->checkPayment($this->payment_id);
        if(!$this->payment || $this->payment != $res->getStatus()){
            $this->payment = $res->getStatus();
            if($res->getStatus() == 'succeeded'){

                $this->sendPaymentEmail();

                $this->status = self::STATUS_PAID;
            } elseif($res->getStatus() == 'canceled'){
                $this->status = self::STATUS_PAYMENT;
                if($res->getCancellationDetails())
                    $this->payment_error = $res->getCancellationDetails()->getReason();
            }
            $this->save();
        }
    }

    public function sendPaymentEmail()
    {
        $emails = [Yii::$app->params['adminEmail']];
        if($this->email)
            $emails[] = $this->email;
        StaticFunction::sendEmail(
            $this,
            'payment',
            $emails,
            'Заказ #' . $this->id . ' успешно оплачен.');
    }

    public function payment(){

        $payment = new Payment();
        $res = $payment->payment($this);
        $this->payment = $res->getStatus();
        $this->payment_id = $res->getId();
        $paymentUrl = $res->getConfirmation()->getConfirmationUrl();
        $this->payment_url = $paymentUrl;
        $this->save();

        return $paymentUrl;
    }

    public static function getShippingCost($shippingMethod){

        $cart = \Yii::$app->cart;
        $total = $cart->getCost();
        if($total >= Yii::$app->params['freeShippingSum']){
            return 0;
        } else {
            if($shippingMethod == 'tk' || $shippingMethod == 'rp'){
                $cookies = Yii::$app->request->cookies;
                $location = $cookies->getValue('location');
                if($location == 'Новосибирск'){
                    return Yii::$app->params['shippingCostNsk'];
                } else {
                    $zones = Yii::$app->params['shippingZones'];
                    foreach ($zones as $zone){
                        if(in_array($location, $zone['cities'])){
                            return $zone['cost'];
                        }
                    }
                }
            }
            return Yii::$app->params['shippingCostDefault'];
        }
    }

    public static function getShippingMethod(){
        $cart = \Yii::$app->cart;
        $total = $cart->getCost(true);
        if($total >= Yii::$app->params['freeShippingSum']) {
            $shippingMethods = Order::getShippingMethodsFree();
        } else {
            $cookies = Yii::$app->request->cookies;
            $location = $cookies->getValue('location');
            if ($location == 'Новосибирск') {
                $shippingMethods = Order::getShippingMethodsNsk();
            } elseif(in_array($location, Yii::$app->params['shippingZones'][1]['cities']) || in_array($location, Yii::$app->params['shippingZones'][2]['cities'])) {
                $shippingMethods = Order::getShippingMethodsZone1();
            } else {
                $shippingMethods = Order::getShippingMethods();
            }
        }
        return $shippingMethods;
    }

    public function getNormalPhone(){
        return '7' . substr($this->phone, -10);
    }
}
