<?php

namespace common\models;

use yii\web\UploadedFile;
use Imagine\Image\Box;
use Yii;

/**
 * This is the model class for table "product_diversity".
 *
 * @property int $id
 * @property int $product_id
 * @property string $image_id
 * @property string $article
 * @property string $title
 * @property int $is_in_stock
 * @property int $is_active
 * @property int $count
 * @property string $color
 *
 * @property Product $product
 * @property Image $image
 */
class ProductDiversity extends \yii\db\ActiveRecord
{

    /**
     * @var UploadedFile[]
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_diversity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'is_in_stock', 'is_active', 'count', 'image_id'], 'integer'],
            [['article', 'color', 'title'], 'string', 'max' => 255],
            [['article'], 'unique'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
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
            'imageFile' => 'Фото',
            'article' => 'Артукул',
            'title' => 'Название',
            'is_in_stock' => 'В наличии',
            'is_active' => 'Показывать',
            'count' => 'Количество',
            'color' => 'Цвет',
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
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function upload()
    {
        Yii::debug('diversityid #' . $this->id, 'order');
        if ($this->validate()) {
            $image = new Image();
            if ($image->save()) {
                Yii::debug('imageid1 #' . $image->id, 'order');
                $this->imageFile->saveAs($image->getPath());
                \yii\imagine\Image::getImagine()
                    ->open($image->getPath())
                    ->thumbnail(new Box(Yii::$app->params['productOriginalImageWidth'], Yii::$app->params['productOriginalImageHeight']))
                    ->save($image->getPath('origin', ['quality' => 80]))
                    ->thumbnail(new Box(Yii::$app->params['productMediumImageWidth'], Yii::$app->params['productMediumImageHeight']))
                    ->save($image->getPath('medium', ['quality' => 80]))
                    ->thumbnail(new Box(Yii::$app->params['productSmallImageWidth'], Yii::$app->params['productSmallImageHeight']))
                    ->save($image->getPath('small', ['quality' => 80]));
            }
            return $image;
        } else {
            return false;
        }
    }

    public static function getDiversityDDArray($productId = null)
    {
        $divArray = [];
        if($productId){
            $diversities = ProductDiversity::find()
                ->select(['*', 'CONCAT(title , \' (\', count,\' шт)\') as title'])
                ->where(['product_id' => $productId])
                ->all();
            foreach ($diversities as $diversity){
                $divArray[] = [
                    'id' => $diversity->id,
                    'name' => $diversity->title,
                ];
            }
        }
        return $divArray;
    }
}
