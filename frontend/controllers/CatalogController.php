<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
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
        /** @var Category $category */
        $category = null;

        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();

        $productsQuery = Product::find()->where(['is_active' => 1]);

        $get = Yii::$app->request->get();
        $this->prepareFilter($productsQuery);

        if ($categorySlug !== null) {
            $category = Category::find()->where(['slug' => $categorySlug])->one();
        }
        if ($category) {
            $productsQuery->andWhere(['category_id' => $this->getCategoryIds($categories, $category->id)]);
        }
//        elseif($categorySlug == 'novelty'){
//            $productsQuery->andWhere(['is_novelty' => 1]);
//        }
        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => isset($get['limit'])? $get['limit']: Yii::$app->params['catalogPageSize'],
            ],
        ]);
        $relatedProducts = Product::find()
            ->where(['is_active' => 1, 'is_in_stock' => 1])
            ->limit(Yii::$app->params['productPageRelatedCount'])
            ->all();
        return $this->render('list', [
            'category' => isset($category)? $category : null,
            'menuItems' => $this->getMenuItems($categories, isset($category->id) ? $category->id : null),
            'models' => $productsDataProvider->getModels(),
            'pagination' => $productsDataProvider->getPagination(),
            'pageCount' => $productsDataProvider->getCount(),
            'relatedProducts' => $relatedProducts,
        ]);
    }

    private function prepareFilter(&$query){
        if($get = Yii::$app->request->get()){
            if(isset($get['color']) && $get['color'] != 'all'){
                $query->andFilterWhere(['like', 'color', $get['color']]);
            }
            if(isset($get['tags']) && $get['tags'] != 'all'){
                $query->andFilterWhere(['like', 'tags', $get['tag']]);
            }
            if(isset($get['price']) && $get['price'] != 'all'){
                $priceArr = explode(',', $get['price']);
                $query->andWhere(['between', 'price', $priceArr[0], $priceArr[1]]);
            }
            if(isset($get['order'])){
                if($get['order'] == 'popular') {
                    $query->orderBy('sort DESC, id DESC');
                } elseif ($get['order'] == 'novelty') {
                    $query->orderBy('is_novelty');
                } elseif ($get['order'] == 'price'){
                    $query->orderBy('price_lh DESC');
                } elseif ($get['order'] == 'price'){
                    $query->orderBy('price_hl ASC');
                }
            } else {
                $query->orderBy('time DESC');
            }
        } else {
            $query->orderBy('time DESC');
        }
    }

    public function actionProduct($categorySlug, $productId)
    {
        $product = Product::find()->where(['id' => $productId])->one();

        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();

        if($product->is_active){
            $category = Category::find()->where(['slug' => $categorySlug])->one();
            $relatedProducts = Product::find()
                ->where('id != :id', ['id'=>$productId])
                ->andWhere(['is_active' => 1, 'is_in_stock' => 1])
                ->limit(Yii::$app->params['productPageRelatedCount'])
                ->all();
            return $this->render('product', [
                'category' => $category,
                'product' => $product,
                'relatedProducts' => $relatedProducts,
                'menuItems' => $this->getMenuItems($categories, isset($category->id) ? $category->id : null),
//                'images' => $imagesForZoom,
            ]);
        } else {
            return $this->redirect('/catalog/list');
        }
    }

    public function actionView()
    {
        return $this->render('view');
    }

    /**
     * @param Category[] $categories
     * @param int $activeId
     * @param int $parent
     * @return array
     */
    private function getMenuItems($categories, $activeId = null, $parent = null)
    {
        $menuItems = [];
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $menuItems[$category->id] = [
                    'active' => $activeId === $category->id,
                    'label' => $category->title,
                    'url' => ['catalog/list', 'id' => $category->id],
                    'items' => $this->getMenuItems($categories, $activeId, $category->id),
                ];
            }
        }
        return $menuItems;
    }


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
