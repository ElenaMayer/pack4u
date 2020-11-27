<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $description
 * @property string $slug
 * @property integer $is_active
 * @property string $time
 * @property integer $sort
 *
 * @property Category $parent
 * @property Category[] $categories
 * @property Product[] $products
 */
class Category extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'is_active', 'sort'], 'integer'],
            [['time'], 'safe'],
            [['title', 'slug', 'description'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Родительский ID',
            'title' => 'Название',
            'description' => 'Описание',
            'slug' => 'Url',
            'is_active' => 'Показать',
            'time' => 'Дата создания',
            'sort' => 'Сортировка',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['category_id' => 'id']);
    }

    public static function getCategoryList()
    {
        $parents = Category::find()->select(['id', 'title'])->where(['parent_id' => null])->all();
        return ArrayHelper::map($parents, 'id', 'title');
    }

    /**
     * @param Category[] $categories
     * @param int $activeId
     * @param int $parent
     * @return array
     */
    public static function getMenuItems($activeId = null, $parent = null)
    {
        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('sort DESC, id')->all();

        if($activeId) {
            $activeCategory = Category::findOne($activeId);
            if($activeCategory && $activeCategory->parent){
                $activeParentId = $activeCategory->parent->id;
            }
        }
        if(!$parent) {
            $menuItems = ['0' => [
                'active' => ($activeId == 'all') ? 1 : 0,
                'label' => 'Все',
                'url' => ['/catalog']
            ]
            ];
        } else {
            $menuItems = [];
        }
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $menuItems[$category->id] = [
                    'active' => $activeId === $category->id,
                    'label' => $category->title,
                    'url' => ['/catalog/'.$category->slug],
                ];
                if($category->slug == 'sale'){
                    $menuItems[$category->id]['options'] = ['class'=>'red'];
                } elseif ($category->slug == 'newyear'){
                    $menuItems[$category->id]['options'] = ['class'=>'newyear fa fa-tree'];
                }
                if($activeId === $category->id || (isset($activeParentId) && $activeParentId === $category->id))
                    $menuItems[$category->id]['items'] = Category::getMenuItems($activeId, $category->id);
            }
        }
        return $menuItems;
    }

    public static function getMainCategories()
    {
        $categories = Category::find()
            ->where(['parent_id' => null, 'is_active' => 1])
            ->indexBy('id')
            ->orderBy('sort DESC, id')
            ->all();
        return $categories;
    }

    public static function getCategoryArray()
    {
        $categories = Category::find()->where(['parent_id' => null])->all();
        $categoryArray = ArrayHelper::map($categories, 'id', 'title');
        return $categoryArray;
    }

    public static function getSubcategoryArray($parent_id = null)
    {
        $subcategoryArray = [];
        if($parent_id){
            $subcategories = Category::find()->where(['parent_id' => $parent_id])->all();
            $subcategoryArray = ArrayHelper::map($subcategories, 'id', 'title');
        }
        return $subcategoryArray;
    }

    public static function getSubcategoryDDArray($parent_id = null)
    {
        $subcategoryArray = [];
        if($parent_id){
            $subcategories = Category::find()->where(['parent_id' => $parent_id])->all();
            foreach ($subcategories as $subcategory){
                $subcategoryArray[] = [
                    'id' => $subcategory->id,
                    'name' => $subcategory->title,
                ];
            }
        }
        return $subcategoryArray;
    }
}
