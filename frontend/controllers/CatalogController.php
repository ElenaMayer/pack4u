<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use common\models\StaticFunction;
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
        $get = Yii::$app->request->get();
        if (!empty($get) && isset($get['urlParams'])){
            $this->redirect($get['urlParams']);
        }

        /** @var Category $category */
        $category = null;

        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();

        $productsQuery = Product::find()->where(['is_active' => 1]);

        $this->prepareFilter($productsQuery);

        if ($categorySlug !== null) {
            $category = Category::find()->where(['slug' => $categorySlug])->one();
        }
        if ($category) {
            $productsQuery->andWhere(['category_id' => $this->getCategoryIds($categories, $category->id)]);
        }
        $productsDataProvider = new ActiveDataProvider([
            'query' => $productsQuery,
            'pagination' => [
                'pageSize' => isset($get['limit'])? $get['limit']: Yii::$app->params['catalogPageSize'],
            ],
        ]);
        $noveltyProducts = Product::find()
            ->andWhere(['is_active' => 1, 'is_in_stock' => 1, 'is_novelty' => 1])
            ->limit(Yii::$app->params['productNewCount'])
            ->all();
        return $this->render('list', [
            'category' => isset($category)? $category : null,
            'menuItems' => $this->getMenuItems(isset($category->id) ? $category->id : 'all'),
            'models' => $productsDataProvider->getModels(),
            'pagination' => $productsDataProvider->getPagination(),
            'pageCount' => $productsDataProvider->getCount(),
            'noveltyProducts' => $noveltyProducts,
        ]);
    }

    private function prepareFilter(&$query){
        if($get = Yii::$app->request->get()){
            if(isset($get['color']) && $get['color'] != 'all'){
                $query->andFilterWhere(['like', 'color', $get['color']]);
            }
            if(isset($get['tag']) && $get['tag'] != 'all'){
                $query->andFilterWhere(['like', 'tags', $get['tag']]);
            }
            if(isset($get['min_price']) && isset($get['max_price'])){
                $query->andWhere(['between', 'price', $get['min_price'], $get['max_price']]);
            }
            if(isset($get['order'])){
                if($get['order'] == 'popular') {
                    $query->orderBy('id DESC');
                } elseif ($get['order'] == 'novelty') {
                    $query->orderBy('is_novelty');
                } elseif ($get['order'] == 'price_lh'){
                    $query->orderBy('price ASC');
                } elseif ($get['order'] == 'price_hl'){
                    $query->orderBy('price DESC');
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

        if($product->is_active){
            $category = Category::find()->where(['slug' => $categorySlug])->one();
//            $relatedProducts = Product::find()
//                ->where('id != :id', ['id'=>$productId])
//                ->andWhere(['is_active' => 1, 'is_in_stock' => 1])
//                ->limit(Yii::$app->params['productPageRelatedCount'])
//                ->all();
            $noveltyProducts = Product::find()
                ->andWhere(['is_active' => 1, 'is_in_stock' => 1, 'is_novelty' => 1])
                ->limit(Yii::$app->params['productNewCount'])
                ->all();
            return $this->render('product', [
                'category' => $category,
                'product' => $product,
                'noveltyProducts' => $noveltyProducts,
//                'relatedProducts' => $relatedProducts,
                'menuItems' => $this->getMenuItems(null)
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
    private function getMenuItems($activeId = null, $parent = null)
    {

        $categories = Category::find()->where(['is_active' => 1])->indexBy('id')->orderBy('id')->all();
        $params = StaticFunction::getParamFromCurrentUrl();
        $menuItems = ['0' => [
            'active' => ($activeId == 'all')?1:0,
            'label' => 'Все',
            'url' => ['/catalog'.$params]
            ]
        ];
        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $menuItems[$category->id] = [
                    'active' => $activeId === $category->id,
                    'label' => $category->title,
                    'url' => ['/catalog/'.$category->slug.$params],
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
