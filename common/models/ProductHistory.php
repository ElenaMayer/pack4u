<?php

namespace common\models;

/**
 * This is the model class for table "product_history".
 *
 * @property int $id
 * @property int $product_id
 * @property int $diversity_id
 * @property int $user_id
 * @property int $order_id
 * @property int $order_item_id
 * @property string $title
 * @property int $count_old
 * @property int $count_new
 * @property string $created_at
 *
 * @property Product $product
 * @property ProductDiversity $diversity
 * @property User $user
 * @property Order $order
 * @property OrderItem $orderItem
 */
class ProductHistory extends \yii\db\ActiveRecord
{
    public $titles = [
        'add' => 'Добавление',
        'edit' => 'Редактирование',
        'order' => 'Заказ',
        'cancel_order' => 'Отмена заказа',
        'delete_order' => 'Удаление заказа',
        'add_to_order' => 'Добавление в заказ',
        'delete_from_order' => 'Удаление из заказа',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'diversity_id', 'user_id', 'order_id', 'order_item_id', 'count_old', 'count_new'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'diversity_id' => 'Product ID',
            'user_id' => 'User ID',
            'order_id' => 'Order ID',
            'order_item_id' => 'Order ID',
            'title' => 'Описание',
            'count_old' => 'Было',
            'count_new' => 'Стало',
            'created_at' => 'Дата',
        ];
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getDiversity()
    {
        return $this->hasOne(ProductDiversity::className(), ['id' => 'diversity_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getOrderItem()
    {
        return $this->hasOne(OrderItem::className(), ['id' => 'order_item_id']);
    }

    public function getTitleStr()
    {
        return $this->titles[$this->title];
    }
}
