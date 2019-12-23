<?php

namespace common\models;

use frontend\models\Wishlist;
use Yii;
use yii\behaviors\SluggableBehavior;
use yz\shoppingcart\CartPositionInterface;
use frontend\models\MyCartPositionTrait;
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
 * @property integer $price
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
 * @property integer $sort
 * @property string $subcategories
 * @property string $instruction
 * @property integer $multiprice
 * @property integer $diversity
 *
 * @property Image[] $images
 * @property OrderItem[] $orderItems
 * @property Category $category
 * @property ProductRelation[] $relations
 * @property ProductPrice[] $prices
 * @property ProductDiversity[] $diversities
 * @property ProductDiversity[] $diversitiesForSearch
 */
class Product extends \yii\db\ActiveRecord implements CartPositionInterface
{
    use MyCartPositionTrait;

    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    public $relationsArr;

    private $_productPrices;
    private $_productDiversities;

    public $group_cnt;
    public $group_sum;

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
            [['category_id', 'is_in_stock', 'is_active', 'is_novelty', 'new_price', 'count', 'sort', 'price', 'multiprice', 'diversity'], 'integer'],
            ['weight', 'match', 'pattern' => '/^[0-9]+[0-9,.]*$/', 'message' => 'Значение должно быть числом.'],
            [['title', 'article', 'category_id', 'weight'], 'required'],
            [['time, color, tags, subcategories'], 'safe'],
            [['slug', 'article', 'size'], 'string', 'max' => 255],
            [['title', 'instruction'], 'string', 'max' => 40],
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
            'subcategories' => 'Подкатегория',
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
            'relationsArr' => 'Связанные товары',
            'sort' => 'Сортировка',
            'instruction' => 'Youtube инструкция',
            'multiprice' => 'Цена от количества',
            'diversity' => 'Расцветки'
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

    /**
     * @return ProductPrice[]
     */
    public function getPrices()
    {
        return $this->hasMany(ProductPrice::className(), ['product_id' => 'id']);
    }

    /**
     * @return ProductDiversity[]
     */
    public function getDiversities()
    {
        return $this->hasMany(ProductDiversity::className(), ['product_id' => 'id']);
    }

    /**
     * @return ProductDiversity[]
     */
    public function getDiversitiesForSearch()
    {
        return $this->hasOne(ProductDiversity::className(), ['product_id' => 'id']);
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
    public function getPrice($qty = 0, $orderCreated = false, $diversityId = null)
    {
        if (($this->getIsActive() && $this->getIsInStock()) || $orderCreated){
            if($this->multiprice)
                return $this->getMultiprice($qty);
            elseif($this->getNewPrice())
                return $this->getNewPrice();
            else
                return $this->price;
        } else {
            return 0;
        }
    }

    public function getIsActive($diversityId = null)
    {
        $product = Product::findOne($this->id);
        if($product->diversity && $diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            return $diversity->is_active;
        } else {
            return $product->is_active;
        }
    }

    public function getIsInStock($diversityId = null)
    {
        $product = Product::findOne($this->id);
        if($product->diversity && $diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            return ($diversity->count > 0);
        } else {
            if($product->diversity){
                foreach ($product->diversities as $div){
                    if($div->count > 0)
                        return true;
                }
                return false;
            } else {
                return ($product->is_in_stock && $product->count > 0);
            }
        }
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

    public function minusCount($count, $diversityId = null){
        if($diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            if($diversity) {
                $diversity->count -= $count;
                $diversity->save(false);
                if ($diversity->count <= 0) {
                    $activeDiv = 0;
                    foreach ($this->diversities as $div){
                        if($div->is_active && $div->count > 0){
                            $activeDiv = 1;
                            break;
                        }
                    }
                    if($activeDiv == 0) {
                        $this->is_in_stock = 0;
                        $this->save(false);
                    }
                }
            }
        } else {
            $this->count -= $count;
            if ($this->count <= 0) {
                $this->is_in_stock = 0;
            }
            $this->save(false);
        }
    }

    public function plusCount($count, $diversityId = null){
        if($diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            if($diversity) {
                $diversity->count += $count;
                $diversity->save(false);
                if ($diversity->count > 0 && $this->is_in_stock == 0) {
                    $this->is_in_stock = 1;
                    $this->save(false);
                }
            }
        } else {
            $this->count += $count;
            if ($this->count > 0) {
                $this->is_in_stock = 1;
            }
            $this->save(false);
        }
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

    public static function getProductArr($is_active = false, $is_in_stock = false)
    {
        $model = Product::find()
            ->select(['*', 'CONCAT(article, \' - \', title , \' (\', count,\' шт)\') as description']);

        if($is_active) {
            $model = $model->andWhere(['is_active' => 1]);
        }
        if($is_in_stock){
            $model = $model->andWhere(['is_in_stock' => 1])->andWhere(['>', 'count', '0']);
        }
        $model = $model->all();
        return ArrayHelper::map($model, 'id', 'description');
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->weight = str_replace(',', '.', $this->weight);
            if(!$this->diversity) {
                if ($this->count > 0)
                    $this->is_in_stock = 1;
                else
                    $this->is_in_stock = 0;
            }
            return true;
        }
        return false;
    }

    public function getSubcategory()
    {
        preg_match('/^[0-9]+,|^[0-9]+$/', $this->subcategories, $subcatId);
        if(!empty($subcatId))
            return Category::findOne(trim($subcatId[0], ','));
        else
            return null;
    }

    public function getProductPrices()
    {
        if ($this->_productPrices === null) {
            $this->_productPrices = $this->isNewRecord ? [] : $this->prices;
        }
        return $this->_productPrices;
    }

    private function getProductPrice($key)
    {
        $price = $key && strpos($key, 'new') === false ? ProductPrice::findOne($key) : false;
        if (!$price) {
            $price = new ProductPrice();
            $price->loadDefaultValues();
        }
        return $price;
    }

    public function setProductPrices($prices)
    {
        unset($prices['__id__']); // remove the hidden "new ProductPrice" row
        $this->_productPrices = [];
        foreach ($prices as $key => $price) {
            if (is_array($price)) {
                $this->_productPrices[$key] = $this->getProductPrice($key);
                $this->_productPrices[$key]->setAttributes($price);
            } elseif ($price instanceof ProductPrice) {
                $this->_productPrices[$price->id] = $price;
            }
        }
    }
    public function getProductDiversities()
    {
        if ($this->_productDiversities === null) {
            $this->_productDiversities = $this->isNewRecord ? [] : $this->diversities;
        }
        return $this->_productDiversities;
    }

    private function getProductDiversity($key)
    {
        $diversity = $key && strpos($key, 'new') === false ? ProductDiversity::findOne($key) : false;
        if (!$diversity) {
            $diversity = new ProductDiversity();
            $diversity->loadDefaultValues();
        }
        return $diversity;
    }

    public function setProductDiversities($diversities)
    {
        unset($diversities['__id__']); // remove the hidden "new ProductDiversity" row
        $this->_productDiversities = [];
        foreach ($diversities as $key => $diversity) {
            if (is_array($diversity)) {
                $this->_productDiversities[$key] = $this->getProductDiversity($key);
                $this->_productDiversities[$key]->setAttributes($diversity);
            } elseif ($diversity instanceof ProductDiversity) {
                $this->_productDiversities[$diversity->id] = $diversity;
            }
        }
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        $this->savePrices();
        $this->saveDiversities();
    }

    public function savePrices()
    {
        $keep = [];
        foreach ($this->productPrices as $price) {
            if($price->price) {
                $price->product_id = $this->id;
                if (!$price->save(false)) {
                    return false;
                }
                $keep[] = $price->id;
            }
        }
        $query = ProductPrice::find()->andWhere(['product_id' => $this->id]);
        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }
        foreach ($query->all() as $price) {
            $price->delete();
        }
        return true;
    }

    public function saveDiversities()
    {
        $keep = [];
        foreach ($this->productDiversities as $uploadId => $diversity) {
            if($diversity->article) {
                $diversity->product_id = $this->id;
                if ($diversity->save()) {
                    $diversity->imageFile = UploadedFile::getInstanceByName('ProductDiversity['.$uploadId.'][imageFile]');
                    if($diversity->imageFile) {
                        if($image = $diversity->upload()){
                            if($diversity->image_id){
                                $imageO = Image::findOne($diversity->image_id);
                                if($imageO) {
                                    $imageO->delete();
                                }
                            }
                            $diversity->image_id = $image->id;
                            $diversity->save(false);
                        }
                    }
                } else {
                    return false;
                }
                $keep[] = $diversity->id;
            }
        }
        $query = ProductDiversity::find()->andWhere(['product_id' => $this->id]);
        if ($keep) {
            $query->andWhere(['not in', 'id', $keep]);
        }
        foreach ($query->all() as $diversity) {
            $diversity->delete();
        }
        return true;
    }

    public function getMultiprice($quantity){
        $result = 0;
        foreach ($this->prices as $price){
            if($quantity >= $price->count){
                $result = $price->price;
            }
        }
        return $result;
    }

    public function getMultipricesStr(){
        $result = '';
        foreach ($this->prices as $price){
            $result .= $price->price . '/';
        }
        return trim($result, '/');
    }

    public function getMultipricesStrFull(){
        $result = '';
        foreach ($this->prices as $price){
            $result .= 'от ' . $price->count . 'шт - ' . $price->price . 'р <br>';
        }
        return $result;
    }

    public function getMinMultiprice(){
        if($this->diversity){
            $count = 0;
            foreach ($this->diversities as $diversity){
                if($diversity->count > 0)
                    $count = $diversity->count;
            }
            return ProductPrice::find()
                ->where(['product_id' => $this->id])
                ->andWhere(['<=', 'count', $count])
                ->min('price');
        } else {
            return ProductPrice::find()
                ->where(['product_id' => $this->id])
                ->andWhere(['<=', 'count', $this->count])
                ->min('price');
        }
    }

    public static function getSizesArray($categoryId = null, $type = false){
        if($categoryId) {
            $models = Product::find()
                ->select(['COUNT(id) AS group_cnt', 'SUM(count) AS group_sum', 'size'])
                ->where(['is_active' => 1, 'is_in_stock' => 1])
                ->andWhere(['or', ['category_id' => $categoryId], ['subcategories' => $categoryId]])
                ->groupBy('size')
                ->all();
            $sizes = [];
            foreach ($models as $m)
            {
                if($type == 'full' || count($models) <= Yii::$app->params['sizeFilterMinCount']){
                    $sizes[$m->size] = $m->size;
                } else {
                    if ($m->size && ($m->group_cnt >= Yii::$app->params['sizeFilterMinCount'] || $m->group_sum > Yii::$app->params['sizeFilterMinSum'])) {
                        $sizes[$m->size] = $m->size;
                    }
                }
            }
            return StaticFunction::arrayMultiSort($sizes);
        } else {
            return [];
        }
    }

    public function getCartPosition($params = [])
    {
        return Yii::createObject([
            'class' => 'frontend\models\ProductCartPosition',
            'id' => $this->id,
        ]);
    }

    public function getItemCount($diversion_id){
        if($diversion_id) {
            $diversion = ProductDiversity::findOne($diversion_id);
            $count = $diversion->count;
        } else {
            $count = $this->count;
        }
        return $count;
    }

    public function activeDiversitiesCount(){
        $count = 0;
        foreach ($this->diversities as $diversity){
            if($diversity->is_active && $diversity->count > 0){
                $count++;
            }
        }
        return $count;
    }

    public function getImageWithDiversity($diversityId){
        if($diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            if($diversity && $image=$diversity->image){
                return $image->getUrl('small');
            } else {
                return false;
            }
        } else {
            if ($this->images && isset($this->images[0]) && $image = $this->images[0]){
                return $image->getUrl('small');
            } else {
                return false;
            }
        }
    }

    public function getCountWithDiversity($diversityId){
        if($diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
            if($diversity){
                return $diversity->count;
            }
        } else {
            return $this->count;
        }
    }
}
