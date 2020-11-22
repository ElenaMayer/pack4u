<?php

namespace common\models;

use Yii;
use yii\web\Cookie;

/**
 * This is the model class for table "product_notification".
 *
 * @property int $id
 * @property int $product_id
 * @property int $diversity_id
 * @property int $user_id
 * @property string $phone
 * @property string $name
 * @property int $is_active
 * @property string $created_at
 * @property string $comment
 *
 * @property Product $product
 * @property User $user
 */
class ProductNotification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_notification';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'diversity_id', 'user_id', 'is_active'], 'integer'],
            [['created_at'], 'safe'],
            [['phone', 'name'], 'string', 'max' => 255],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Товар',
            'diversity_id' => 'Расцветка',
            'user_id' => 'Пользователь',
            'name' => 'Имя',
            'phone' => 'Телефон',
            'is_active' => 'Активен',
            'created_at' => 'Создан',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function getNotificationsFromCookie(){
        $notifications = Yii::$app->request->cookies->get('notifications');
        if($notifications)
            return json_decode($notifications, true);
        else
            return [];
    }

    public static function addNotificationToCookie($productId, $diversityId = 0){
        $notifications = ProductNotification::getNotificationsFromCookie();
        if(!$notifications)
            $notifications = [];
        $notifications[] = ['productId' => $productId, 'diversityId' => $diversityId];
        Yii::$app->response->cookies->add(new Cookie([
            'name' => 'notifications',
            'value' => json_encode($notifications),
        ]));
    }

    public static function isNotificationExists($productId, $diversityId = 0){
        $notifications = ProductNotification::getNotificationsFromCookie();
        foreach ($notifications as $notification){
            if($notification['productId'] == $productId){
                if($diversityId){
                    if($notification['diversityId'] == $diversityId){
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }
        if(!Yii::$app->user->isGuest){
            $conditions = ['user_id' => Yii::$app->user->id, 'product_id' => $productId];
            if($diversityId){
                $conditions['diversity_id'] = $diversityId;
            }
            $notification = ProductNotification::findOne($conditions);
            if($notification){
                return true;
            }
        }
        return false;
    }
}
