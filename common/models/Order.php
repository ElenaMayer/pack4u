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
 * @property integer $payment
 * @property string $city
 * @property string $shipping_method
 * @property string $payment_method
 * @property integer $zip
 * @property string $tk
 * @property string $rcr
 * @property string $pickup_time
 * @property integer $discount
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
            [['created_at', 'updated_at', 'shipping_cost', 'zip', 'payment', 'discount'], 'integer'],
            [['address', 'notes'], 'string'],
            [['phone', 'email', 'status', 'fio', 'city', 'shipping_method', 'payment_method', 'tk', 'rcr', 'pickup_time'], 'string', 'max' => 255],
            [['phone', 'fio'], 'required'],
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
            'city' => 'Город, Пункт выдачи',
            'shipping_method' => 'Способ доставки',
            'payment_method' => 'Способ оплаты',
            'zip' => 'Индекс',
            'tk' => 'Транспортная компания',
            'rcr' => 'Пункт выдачи РЦР',
            'pickup_time' => 'Время выдачи',
            'discount' => 'Скидка',
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
            self::STATUS_PAYMENT => 'Ожидает оплаты',
            self::STATUS_PAID => 'Оплачено',
            self::STATUS_COLLECTED => 'Собран',
            self::STATUS_PACKED => 'Упакован',
            self::STATUS_SHIPPED => 'Передан в доставку',
            self::STATUS_PRE_ORDER => 'Предзаказ',
            self::STATUS_DONE => 'Выполнен',
            self::STATUS_CANCELED => 'Отменен',
        ];
    }

    public static function getShippingMethods()
    {
        return [
            'self' => 'Самовывоз (по договоренности)',
            'courier' => 'Курьер (для Новосибирска)',
            'rcr' => 'РЦР (для Новосибирска)',
            'rp' => 'Почта России',
            'tk' => 'Транспортная компания',
        ];
    }

    public static function getShippingMethodsLite()
    {
        return [
            'self' => 'Самовывоз',
            'courier' => 'Курьер',
            'rcr' => 'РЦР',
            'rp' => 'Почта России',
            'tk' => 'ТК',
        ];
    }

    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Наличными при получении',
            'card' => 'На карту',
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

    public function sendEmail()
    {
        $emails = [Yii::$app->params['adminEmail']];
        if($this->email)
            $emails[] = $this->email;
        return Yii::$app->mailer->compose('order', ['order' => $this])
            ->setTo($emails)
            ->setFrom([Yii::$app->params['adminEmail'] => Yii::$app->params['title']])
            ->setSubject('Заказ #' . $this->id . ' создан.')
            ->send();
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
}
