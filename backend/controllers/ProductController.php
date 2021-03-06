<?php

namespace backend\controllers;

use common\models\Category;
use common\models\Image;
use common\models\ProductDiversity;
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

            if(!$model->diversity) {
                Yii::debug('Добавлен товар Арт.' . $model->article . ': ' . $model->count . 'шт', 'order');
            } else {
                Yii::debug('Добавлен товар Арт.' . $model->article . ' ->', 'order');
                foreach ($model->diversities as $diversity){
                    Yii::debug('Расцветка Арт.' . $diversity->article . ': ' . $diversity->count . 'шт', 'order');
                }
            }

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
            if (is_array($post['ProductDiversity']))
            {
                $model->productDiversities = $post['ProductDiversity'];

                $isCountChange = false;
                foreach ($post['ProductDiversity'] as $id => $div){
                    if($id != '__id__') {
                        if (strpos($id, 'new') === false) {
                            $oldDiv = ProductDiversity::findOne($id);
                            if ($oldDiv->count != $div['count']) {
                                Yii::debug('Расцветка Арт.' . $div['article'] . ' ' . $oldDiv->count . ' -> ' . $div['count'] . 'шт', 'order');
                                $isCountChange = true;
                            }
                        } else {
                            if ($div['count'] && $div['article']) {
                                Yii::debug('Добавлена расцветка Арт.' . $div['article'] . ': ' . $div['count'] . 'шт', 'order');
                                $isCountChange = true;
                            }
                        }
                    }
                }
                if($isCountChange) {
                    Yii::debug('<- Редактирование товара Арт.' . $model->article, 'order');
                }
            }
            if(!$model->diversity && $post['Product']['count'] != $model->count) {

                Yii::debug('Редактирование товар Арт.' . $model->article . ' ' . $model->count . ' -> ' . $post['Product']['count']  . 'шт', 'order');

                if ($post['Product']['count'] <= 0 && $model->is_in_stock == 1) {
                    $post['Product']['is_in_stock'] = 0;
                }
            }
            if ($model->load($post) && $model->save()) {
                if (is_array($post['Product']['relationsArr']))
                {
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
