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
 * @property integer $discount
 *
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DONE = 'done';
    const STATUS_CANCELED = 'canceled';
    const STATUS_PAYMENT = 'payment';
    const STATUS_PRE_ORDER = 'pre_order';

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
            [['phone', 'email', 'status', 'fio', 'city', 'shipping_method', 'payment_method', 'tk', 'rcr'], 'string', 'max' => 255],
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
            'city' => 'Город',
            'shipping_method' => 'Способ доставки',
            'payment_method' => 'Способ оплаты',
            'zip' => 'Индекс',
            'tk' => 'Транспортная компания',
            'rcr' => 'Пункт выдачи РЦР',
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
            if ($this->isNewRecord) {
                $this->status = self::STATUS_NEW;
            } else {
                $oldAttributes = $this->getOldAttributes();
                if($this->status != $oldAttributes['status']) {
                    if($this->status == self::STATUS_CANCELED) {
                        foreach ($this->orderItems as $item){
                            $item->product->count += $item->quantity;
                            $item->product->save();
                        }
                    } elseif($oldAttributes['status'] == self::STATUS_CANCELED){
                        foreach ($this->orderItems as $item){
                            $item->product->count -= $item->quantity;
                            $item->product->save();
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
            self::STATUS_IN_PROGRESS => 'В обработке',
            self::STATUS_PAYMENT => 'Ожидает оплаты',
            self::STATUS_SHIPPED => 'Передан в доставку',
            self::STATUS_DONE => 'Выполнен',
            self::STATUS_CANCELED => 'Отменен',
            self::STATUS_PRE_ORDER => 'Предзаказ',
        ];
    }

    public static function getShippingMethods()
    {
        return [
            'self' => 'Самовывоз',
            'rcr' => 'РЦР',
            'rp' => 'Почта России',
            'tk' => 'Транспортная компания',
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
            'dellin' => 'Деловые линии',
            'cdek' => 'СДЭК',
            'pec' => 'ПЭК',
            'nrg' => 'Энергия',
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
    
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        foreach ($this->orderItems as $item) {
            $product = Product::findOne($item->product_id);
            $product->plusCount($item->quantity);
        }

        return true;
    }

}
