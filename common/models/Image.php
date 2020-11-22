<?php

namespace common\models;

use Yii;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\ManipulatorInterface;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property integer $product_id
 *
 * @property Product $product
 * @property ProductDiversity[] $diversities
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getDiversities()
    {
        return $this->hasMany(ProductDiversity::className(), ['image_id' => 'id']);
    }

    //'origin': $size = '1200x1600'
    //'medium': $size = '540x960';
    //'small': $size = '140x249';

    public function getPath($size = 'origin')
    {
        return Yii::getAlias('@frontend/web/uploads/product/' . $this->id . '_' . $size . '.jpg');
    }

    public function getPathById($id, $size = 'origin')
    {
        return Yii::getAlias('@frontend/web/uploads/product/' . $id . '_' . $size . '.jpg');
    }

    public function getUrl($size = 'origin')
    {
        return Yii::getAlias('@frontendWebroot/uploads/product/' . $this->id . '_' . $size . '.jpg');
    }

    public function afterDelete()
    {
        unlink($this->getPath('small'));
        unlink($this->getPath('medium'));
        unlink($this->getPath('origin'));
        parent::afterDelete();
    }

    public function prepareImage(){

        $wR = Yii::$app->params['productOriginalImageWidth'];
        $hR = Yii::$app->params['productOriginalImageHeight'];
        $i = \yii\imagine\Image::getImagine()
            ->open($this->getPath('origin'))
            ->thumbnail(new Box($wR, $hR), ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $size = $i->getSize();
        $wC = $size->getWidth();
        $hC = $size->getHeight();

        // Current img < result img
        if($wR > $wC && $hR > $hC){
            if($hC > $wC && ($hC/$wC > $hR/$wR)){
                $this->cropHeight($i, $wC, $hC, $wR, $hR);
            } elseif ($hC <= $wC || $hC/$wC < $hR/$wR){
                $this->cropWidth($i, $wC, $hC, $wR, $hR);
            }
        } elseif ($wR > $wC) {
            $this->cropHeight($i, $wC, $hC, $wR, $hR);
        } elseif ($hR > $hC){
            $this->cropWidth($i, $wC, $hC, $wR, $hR);
        }

        $i->save($this->getPath('origin', ['quality' => 80]))
            ->thumbnail(new Box(Yii::$app->params['productMediumImageWidth'], Yii::$app->params['productMediumImageHeight']))
            ->save($this->getPath('medium', ['quality' => 80]))
            ->thumbnail(new Box(Yii::$app->params['productSmallImageWidth'], Yii::$app->params['productSmallImageHeight']))
            ->save($this->getPath('small', ['quality' => 80]));
    }

    private function cropWidth(&$i, $wC, $hC, $wR, $hR){
        $wNew = $wR*$hC/$hR;
        $i->crop(new Point(($wC-$wNew)/2, 0), new Box($wNew, $hC));
    }

    private function cropHeight(&$i, $wC, $hC, $wR, $hR){
        $hNew = $wC*$hR/$wR;
        $i->crop(new Point(0, ($hC - $hNew)/2), new Box($wC, $hNew));
    }

    public function copy($newId){

        $i = \yii\imagine\Image::getImagine()
            ->open($this->getPath('origin'));
        $i->save($this->getPathById($newId, 'origin', ['quality' => 80]))
            ->thumbnail(new Box(Yii::$app->params['productMediumImageWidth'], Yii::$app->params['productMediumImageHeight']))
            ->save($this->getPathById($newId, 'medium', ['quality' => 80]))
            ->thumbnail(new Box(Yii::$app->params['productSmallImageWidth'], Yii::$app->params['productSmallImageHeight']))
            ->save($this->getPathById($newId, 'small', ['quality' => 80]));
    }
}
