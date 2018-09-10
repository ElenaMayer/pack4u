<?php

namespace common\models;

use common\models\ProductRelation;
use frontend\models\Wishlist;
use Yii;
use yii\behaviors\SluggableBehavior;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;
use yii\web\UploadedFile;
use Imagine\Image\Box;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property integer $category_id
 * @property string $price
 * @property string $article
 * @property integer $is_in_stock
 * @property integer $is_active
 * @property integer $is_novelty
 * @property string $size
 * @property string $color
 * @property string $tags
 * @property integer $new_price
 * @property integer $count
 * @property double $weight
 * @property string $time
 *
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property Category $category
 * @property ProductRelation[] $relations
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public $relationsArr;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['category_id', 'is_in_stock', 'is_active', 'is_novelty', 'new_price', 'count'], 'integer'],
            ['weight', 'match', 'pattern' => '/^[0-9]+[0-9,.]*$/', 'message' => 'Значение должно быть числом.'],
            [['title', 'article', 'category_id', 'count', 'price', 'weight'], 'required'],
            [['price'], 'number'],
            [['time, color, tags'], 'safe'],
            [['slug', 'article', 'size'], 'string', 'max' => 255],
            [['title'], 'string', 'max' => 40],
            [['article'], 'unique'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Названи',
            'slug' => 'Slug',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'price' => 'Цена',
            'article' => 'Артикул',
            'is_in_stock' => 'В наличии',
            'is_active' => 'Показывать',
            'is_novelty' => 'Новинка',
            'size' => 'Размер',
            'color' => 'Цвет',
            'tags' => 'Теги',
            'new_price' => 'Новая цена',
            'count' => 'Кол-во',
            'weight' => 'Вес, кг',
            'time' => 'Дата создания',
            'imageFiles' => 'Фото',
            'relationsArr' => 'Связанные товары'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            foreach ($this->imageFiles as $key=>$file) {
                $image = new Image();
                $image->product_id = $this->id;
                if ($image->save()) {
                    $file->saveAs($image->getPath());
                    \yii\imagine\Image::getImagine()
                        ->open($image->getPath())
                        ->thumbnail(new Box(Yii::$app->params['productOriginalImageWidth'], Yii::$app->params['productOriginalImageHeight']))
                        ->save($image->getPath('origin', ['quality' => 80]))
                        ->thumbnail(new Box(Yii::$app->params['productMediumImageWidth'], Yii::$app->params['productMediumImageHeight']))
                        ->save($image->getPath('medium', ['quality' => 80]))
                        ->thumbnail(new Box(Yii::$app->params['productSmallImageWidth'], Yii::$app->params['productSmallImageHeight']))
                        ->save($image->getPath('small', ['quality' => 80]));
                }
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return Image[]
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
     * @return ProductRelation[]
     */
    public function getRelations()
    {
        return $this->hasMany(ProductRelation::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function isInWishlist()
    {
        if (!Yii::$app->user->isGuest) {
            $wishlist = Wishlist::find()
                ->where(['user_id' => Yii::$app->user->id, 'product_id' => $this->id])
                ->one();
            if ($wishlist)
                return true;
            else
                return false;
        } else
            return false;
    }

    /**
     * @inheritdoc
     */
    public function getPrice()
    {
        if ($this->getIsActive() && $this->getIsInStock()){
            if($this->getNewPrice())
                return $this->getNewPrice();
            else
                return $this->price;
        } else {
            return 0;
        }
    }

    public function getIsActive()
    {
        $product = Product::findOne($this->id);
        return $product->is_active;
    }

    public function getIsInStock()
    {
        $product = Product::findOne($this->id);
        if($product->is_in_stock && $product->count > 0)
            return true;
        else
            return false;
    }

    public function getCount()
    {
        $product = Product::findOne($this->id);
        return $product->count;
    }

    public function getNewPrice()
    {
        $product = Product::findOne($this->id);
        return $product->new_price;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAllColorsArray()
    {
        $models = $this->find()->all();
        $colors = [];
        foreach ($models as $m)
        {
            $cs = explode(",",$m->color);
            foreach ($cs as $c)
            {
                if (!in_array($c,$colors))
                {
                    $colors[$c] = $c;
                }
            }
        }
        return $colors;
    }

    public function getColorsArray()
    {
        $colors = [];
        $cs = explode(",",$this->color);
        foreach ($cs as $c)
        {
            if (!in_array($c,$colors))
            {
                $colors[$c] = $c;
            }
        }
        return $colors;
    }

    public static function getTagsArray($is_active = null)
    {
        $models = Product::find();
        if($is_active)
            $models = $models->where(['is_active' => $is_active]);
        $models = $models->all();
        $tags = [];
        foreach ($models as $m)
        {
            $ts = explode(",",$m->tags);
            foreach ($ts as $t)
            {
                if ($t && !in_array($t,$tags))
                {
                    $tags[$t] = $t;
                }
            }
        }
        return $tags;
    }

    public function getCurrentTagsArray()
    {
        $tags = [];
        $ts = explode(",", $this->tags);
        foreach ($ts as $t)
        {
            if (!in_array($t,$tags))
            {
                $tags[$t] = $t;
            }
        }
        return $tags;
    }

    public function getColorStr(){
        return str_replace(',', ', ', $this->color);
    }

    public function saveRelations($relations){
        if($this->relations){
            foreach ($this->relations as $relation){
                $relation->delete();
            }
        }
        foreach ($relations as $relationId){
            $relation = new ProductRelation();
            $relation->parent_id = $this->id;
            $relation->child_id = $relationId;
            $relation->save();
        }
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->relationsArr = ArrayHelper::map($this->relations, 'id', 'child_id');
    }

    public function minusCount($count){
        $this->count -= $count;
        $this->save(false);
    }

    public function plusCount($count){
        $this->count += $count;
        $this->save(false);
    }

    public function getSale(){
        if($this->price && $this->new_price){
            return round(100 - ($this->new_price * 100 / $this->price));
        } else {
            return 0;
        }
    }

    public static function getNovelties(){
        $noveltyProducts = Product::find()
            ->where(['is_active' => 1, 'is_in_stock' => 1, 'is_novelty' => 1])
            ->andWhere(['>', 'count', '0'])
            ->limit(Yii::$app->params['productNewCount'])
            ->all();
        return $noveltyProducts;
    }

    public function getActiveRelations()
    {
        $result = [];
        foreach (array_values($this->relations) as $index => $model){
            if($model->child->getIsActive() && $model->child->getIsInStock()){
                $result[] = $model;
            }
        }
        return $result;
    }

    public static function getActiveProductArr()
    {
        $model = Product::find()
            ->select(['*', 'CONCAT(article, \' - \', title , \' (\', count,\' шт)\') as description'])
            ->where(['is_active' => 1, 'is_in_stock' => 1])
            ->andWhere(['>', 'count', '0'])
            ->all();
        return ArrayHelper::map($model, 'id', 'description');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->weight = str_replace(',', '.', $this->weight);
            return true;
        }
        return false;
    }
}
