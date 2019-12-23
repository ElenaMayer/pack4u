<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use common\models\ProductDiversity;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use Yii;

class CatalogController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            Url::remember();
            return true;
        } else {
            return false;
        }
    }

    public function actionList($categorySlug = null)
    {
        if ($categorySlug == null) {
            $this->redirect('catalog/boxes');
        }
        $get = Yii::$app->request->get();
        if (!empty($get) && isset($get['urlParams'])){
            $this->redirect($get['urlParams']);
        }

        /** @var Category $category */
        $category = null;

        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();

        $productsQuery = Product::find()->where(['is_active' => 1]);

        $this->prepareFilter($productsQuery);

        $category = Category::find()->where(['slug' => $categorySlug])->one();
        if ($category) {
            if(!$category->parent)
                $productsQuery->andWhere(['category_id' => $this->getCategoryIds($categories, $category->id)]);
            else {
                $productsQuery->andWhere(['REGEXP', 'subcategories','^'.$category->id.'$|^'.$category->id.',.+|^.+,'.$category->id.'$|^.+,'.$category->id.',']);
            }
        }
        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => isset($get['limit'])? $get['limit']: Yii::$app->params['catalogPageSize'],
            ],
        ]);
        return $this->render('list', [
            'category' => isset($category)? $category : null,
            'menuItems' => Category::getMenuItems(isset($category->id) ? $category->id : 'all'),
            'models' => $productsDataProvider->getModels(),
            'pagination' => $productsDataProvider->getPagination(),
            'pageCount' => $productsDataProvider->getCount(),
            'noveltyProducts' => Product::getNovelties(),
        ]);
    }

    private function prepareFilter(&$query){
        if($get = Yii::$app->request->get()){
            if(isset($get['color']) && $get['color'] != 'all'){
                $query->andFilterWhere(['like', 'color', $get['color']]);
            }
            if(isset($get['size']) && $get['size'] != 'all'){
                if(strripos($get['size'], ';') === false) {
                    $query->andFilterWhere(['size' => $get['size']]);
                } else {
                    $sizes = array_diff(explode(';', $get['size']), array(''));
                    $query->andWhere(['in','size', $sizes]);
                }
            }
            if(isset($get['tag']) && $get['tag'] != 'all'){
                $query->andFilterWhere(['like', 'tags', $get['tag']]);
            }
            if(isset($get['min_price']) && isset($get['max_price'])){
                $query->andWhere(['between', 'price', $get['min_price'], $get['max_price']]);
            }
            if(isset($get['order'])){
                if ($get['order'] == 'novelty') {
                    $query->orderBy('is_in_stock DESC, is_novelty');
                } elseif ($get['order'] == 'article'){
                    $query->orderBy('article ASC');
                } elseif ($get['order'] == 'price_lh'){
                    $query->select(['*', '(CASE WHEN new_price > 0 THEN new_price ELSE price END) as price_common']);
                    $query->orderBy('price_common ASC');
                } elseif ($get['order'] == 'price_hl'){
                    $query->select(['*', '(CASE WHEN new_price > 0 THEN new_price ELSE price END) as price_common']);
                    $query->orderBy('price_common DESC');
                } else {
                    $query->orderBy('is_in_stock DESC, sort DESC, id DESC');
                }
            } else {
                $query->orderBy('is_in_stock DESC, sort DESC, id DESC');
            }
        } else {
            $query->orderBy('is_in_stock DESC, sort DESC, id DESC');
        }
    }

    public function actionProduct($categorySlug, $productId, $diversityId=null)
    {
        $product = Product::findOne($productId);

        $category = Category::find()->where(['slug' => $categorySlug])->one();
        if($product->diversity && $diversityId){
            $diversity = ProductDiversity::findOne($diversityId);
        }
        if(isset($_GET['ajax']) && $_GET['ajax'] == 1){
            return $this->renderPartial('_product_data', [
                'category' => $category,
                'product' => $product,
                'diversity' => (isset($diversity) && $diversity->is_active)? $diversity : null,
                'diversityId' => $diversityId,
            ]);
        }
        if(!$product || !$product->is_active){
            return $this->redirect('/catalog/list');
        } elseif(!$diversityId && $product->diversity && $product->activeDiversitiesCount() == 1){
            foreach ($product->diversities as $diversity){
                if($diversity->is_active && $diversity->count > 0) {
                    $diversityId = $diversity->id;
                    break;
                }
            }
            $categorySlug = $product->category->slug;
            return $this->redirect("/catalog/$categorySlug/$product->id/$diversityId");
        }

        return $this->render('product', [
            'category' => $category,
            'product' => $product,
            'diversity' => (isset($diversity) && $diversity->is_active)? $diversity : null,
            'noveltyProducts' => Product::getNovelties(),
            'menuItems' => Category::getMenuItems(null)
        ]);
    }

//    public function actionSale()
//    {
//        $get = Yii::$app->request->get();
//        if (!empty($get) && isset($get['urlParams'])){
//            $this->redirect($get['urlParams']);
//        }
//
//        $productsQuery = Product::find()
//            ->where(['is_active' => 1, 'is_in_stock' => 1])
//            ->andWhere(['>', 'new_price', 0])
//            ->andWhere(['>', 'count', 0]);
//
//        $this->prepareFilter($productsQuery);
//
//        $productsDataProvider = new ActiveDataProvider([
//            'query' => $productsQuery,
//            'pagination' => [
//                'pageSize' => isset($get['limit'])? $get['limit']: Yii::$app->params['catalogPageSize'],
//            ],
//        ]);
//
//        $maxSale = Product::find()
//            ->where(['is_active' => 1, 'is_in_stock' => 1])
//            ->andWhere(['>', 'new_price', 0])
//            ->andWhere(['>', 'count', 0])
//            ->max('(price - new_price) * 100 / price');
//
//        return $this->render('sale', [
//            'maxSale' => round($maxSale),
//            'models' => $productsDataProvider->getModels(),
//            'pagination' => $productsDataProvider->getPagination(),
//            'pageCount' => $productsDataProvider->getCount(),
//            'noveltyProducts' => Product::getNovelties(),
//        ]);
//    }

    /**
     * Returns IDs of category and all its sub-categories
     *
     * @param Category[] $categories all categories
     * @param int $categoryId id of category to start search with
     * @param array $categoryIds
     * @return array $categoryIds
     */
    private function getCategoryIds($categories, $categoryId, &$categoryIds = [])
    {
        foreach ($categories as $category) {
            if ($category->id == $categoryId) {
                $categoryIds[] = $category->id;
            }
            elseif ($category->parent_id == $categoryId){
                $this->getCategoryIds($categories, $category->id, $categoryIds);
            }
        }
        return $categoryIds;
    }
}
