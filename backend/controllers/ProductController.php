<?php

namespace backend\controllers;

use common\models\Category;
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
            if ($model->load($post) && $model->save()) {
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                if ($model->upload()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
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
        if($post = Yii::$app->request->post()){
            if (is_array($post['Product']['color']))
            {
                $model->color = implode(",",$post['Product']['color']);
            } else {
                $model->color = '';
            }
            if (is_array($post['Product']['tags']))
            {
                $model->tags = implode(",",$post['Product']['tags']);
            } else {
                $model->tags = '';
            }
            if (is_array($post['Product']['subcategories']))
            {
                $model->subcategories = implode(",",$post['Product']['subcategories']);
            }
            if($post['Product']['count'] != $model->count && $post['Product']['count'] <= 0 && $model->is_in_stock == 1) {
                $post['Product']['is_in_stock'] = 0;
            }
            if ($model->load($post) && $model->save()) {
                if (is_array($post['Product']['relationsArr']))
                {
                    $model->saveRelations($post['Product']['relationsArr']);
                }
                $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
                if ($model->upload()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            $model->color = !empty($model->color)?explode(",",$model->color):[];
            $model->tags = !empty($model->tags)?explode(",",$model->tags):[];
            $model->subcategories = !empty($model->subcategories)?explode(",",$model->subcategories):[];
            return $this->render('update', [
                'model' => $model,
            ]);
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
