<?php

namespace backend\controllers;

use common\models\Category;
use common\models\Image;
use common\models\ProductDiversity;
use common\models\ProductHistory;
use common\models\ProductPrice;
use common\models\ProductRelation;
use Yii;
use common\models\Product;
use backend\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\helpers\Json;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndex_full()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->searchFull(Yii::$app->request->queryParams);

        return $this->render('index_full', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->is_active = 1;
        $model->is_in_stock = 1;
        $model->weight = 0;
        $model->sort = 0;

        if($this->processingProduct($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($this->processingProduct($model)) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionCopy($id){

        $product = Product::findOne($id);
        $productNew = new Product();
        $productNew->attributes = $product->attributes;
        $productNew->is_active = false;
        $productNew->is_in_stock = false;
        $productNew->article = $product->article . '_8';
        $productNew->count = 0;
        $productNew->color = $product->color;
        $productNew->subcategories = $product->subcategories;
        $productNew->tags = $product->tags;
        $productNew->save(false);

        foreach ($product->images as $image){
            $imageNew = new Image();
            $imageNew->product_id = $productNew->id;
            $imageNew->save();
            $image->copy($imageNew->id);
        }

        foreach ($product->diversities as $diversity){
            $imageNew = new Image();
            $imageNew->save();
            $diversityNew = new ProductDiversity();
            $diversityNew->attributes = $diversity->attributes;
            $diversityNew->image_id = $imageNew->id;
            $diversityNew->product_id = $productNew->id;
            $diversityNew->article = $diversity->article . '_8';
            $diversityNew->count = 0;
            $diversityNew->save(false);

            $diversity->image->copy($imageNew->id);
        }

        foreach ($product->relations as $relation){
            $relationNew = new ProductRelation();
            $relationNew->attributes = $relation->attributes;
            $relationNew->parent_id = $productNew->id;
            $relationNew->save();
        }

        foreach ($product->prices as $price){
            $priceNew = new ProductPrice();
            $priceNew->attributes = $price->attributes;
            $priceNew->product_id = $productNew->id;
            $priceNew->save();
        }

        return $this->redirect(['update', 'id' => $productNew->id]);
    }

    public function processingProduct($model){
        if($post = Yii::$app->request->post()) {
            $isNewRecord = $model->isNewRecord;
            if (is_array($post['Product']['color'])) {
                $model->color = implode(",", $post['Product']['color']);
            }
            if (is_array($post['Product']['tags'])) {
                $model->tags = implode(",", $post['Product']['tags']);
            }
            if (is_array($post['Product']['subcategories']))
            {
                $model->subcategories = implode(",",$post['Product']['subcategories']);
            }
            if (is_array($post['ProductPrice']))
            {
                $model->productPrices = $post['ProductPrice'];
            }
            $oldDiv = $newDiv = [];
            if (is_array($post['ProductDiversity']))
            {
                $newDiv = $post['ProductDiversity'];
                if(!$isNewRecord){
                    $oldDiv = $model->productDiversities;
                }
                $model->productDiversities = $post['ProductDiversity'];
            }
            if(!$model->diversity && $post['Product']['count'] != $model->count) {
                if ($post['Product']['count'] <= 0 && $model->is_in_stock == 1) {
                    $post['Product']['is_in_stock'] = 0;
                }
            }
            $oldModel = $model;
            if ($model->load($post) && $model->save()) {
                if(!$model->diversities) {
                    if($isNewRecord){
                        $this->saveHistory('add', $model, 0, $model->count);
                    } elseif($model->count != $oldModel->count){
                        $this->saveHistory('edit', $model, $oldModel->count, $model->count);
                    }
                } else {
                    $oldDivIds = [];
                    foreach ($oldDiv as $div){
                        $oldDivIds[$div->id] = ['count' => $div->count];
                    }
                    foreach ($newDiv as $id => $div){
                        if(!$oldDiv){
                            $this->saveHistory('add', $model, 0, $div['count'], $id);
                        } elseif(isset($oldDivIds[$id]) && $oldDivIds[$id]['count'] != $div['count']) {
                            $this->saveHistory('edit', $model, $oldDivIds[$id]['count'], $div['count'], $id);
                        }
                    }
                }
                if (is_array($post['Product']['relationsArr'])){
                    $model->saveRelations($post['Product']['relationsArr']);
                }
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                if ($model->upload()) {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            $model->color = !empty($model->color)?explode(",",$model->color):[];
            $model->tags = !empty($model->tags)?explode(",",$model->tags):[];
            $model->subcategories = !empty($model->subcategories)?explode(",",$model->subcategories):[];
            return false;
        }
    }

    private function saveHistory($title, $model, $countOld, $countNew, $diversityId = null){
        $history = new ProductHistory();
        $history->title = $title;
        $history->product_id = $model->id;
        $history->count_old = $countOld;
        $history->count_new = $countNew;
        $history->user_id = Yii::$app->user->id;
        if(!$model->diversity) {
            $history->diversity_id = $diversityId;
        }
        $history->save();
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_subcategories()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = Category::getSubcategoryDDArray($cat_id);
                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }
}
