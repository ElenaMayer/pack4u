<?php

namespace backend\models;

use common\models\Product;
use Yii;

/**
 * This is the model class for table "product_diversity_view".
 *
 * @property int $id
 * @property int $diversity_id
 * @property int $category_id
 * @property int $is_in_stock
 * @property int $is_novelty
 * @property int $diversity
 * @property string $size
 * @property int $price
 * @property string $title
 * @property string $article
 * @property int $is_active
 * @property int $count
 */
class ProductDiversityView extends Product
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_diversity_view';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'diversity_id', 'category_id', 'is_in_stock', 'is_novelty', 'diversity', 'price', 'is_active', 'count'], 'integer'],
            [['title'], 'string'],
            [['size', 'article'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
//    public function attributeLabels()
//    {
//        return [
//            'id' => 'Product ID',
//            'diversity_id' => 'Diversity ID',
//            'category_id' => 'Category ID',
//            'is_in_stock' => 'Is In Stock',
//            'is_novelty' => 'Is Novelty',
//            'diversity' => 'Diversity',
//            'size' => 'Size',
//            'price' => 'Price',
//            'title' => 'Title',
//            'article' => 'Article',
//            'is_active' => 'Is Active',
//            'count' => 'Count',
//        ];
//    }
}
