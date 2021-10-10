<?php

namespace backend\controllers;

use common\models\Product;
use common\models\ProductDiversity;
use Yii;
use common\models\Order;
use backend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\OrderItem;
use yii\helpers\Json;
use common\models\Payment;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
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
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if($post = Yii::$app->request->post('OrderItem')){
            $product = Product::findOne($post['product_id']);
            $orderItem = OrderItem::find()->where(['order_id' => $id, 'product_id' => $post['product_id']]);
            $div = [];
            if($post['diversity_id']){
                $div = ProductDiversity::findOne($post['diversity_id']);
                $orderItem = $orderItem->andWhere(['diversity_id' => $post['diversity_id']]);
            }
            $orderItem = $orderItem->one();
            if($orderItem) {
                $orderItem->quantity += $post['quantity'];

                if(!$orderItem->diversity_id) {
                    Yii::debug('Заказ #' . $id . ' изменен Арт.' . $product->article . ' ' . $product->count . ' -> ' . ($product->count-$post['quantity']) . 'шт', 'order');
                } else {
                    Yii::debug('Заказ #' . $id . ' изменен (расц) Арт.' . $orderItem->diversity->article . ' ' . $orderItem->diversity->count . ' -> ' . ($orderItem->diversity->count-$post['quantity']) . 'шт', 'order');
                }

            } else {
                $orderItem = new OrderItem();
                $orderItem->order_id = $id;
                $orderItem->title = $product->title . ($div ? ' "' . $div->title . '"' : '');
                $orderItem->price = $product->getPrice($post['quantity'], true);
                $orderItem->product_id = $post['product_id'];
                $orderItem->quantity = $post['quantity'];
                $orderItem->diversity_id = $post['diversity_id'];
            }
            if ($orderItem->save()) {
                $product->minusCount($post['quantity'], $id, $orderItem->diversity_id);
            }
        }

        $order = $this->findModel($id);
        if($order->payment == 'pending'){
            $order->checkPayment();
        }

        return $this->render('view', [
            'model' => $order,
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Order();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionDelete_item($id)
    {
        $model = OrderItem::findOne($id);
        $product = Product::findOne($model->product_id);
        if($product)
            $product->plusCount($model->quantity, $model->order_id, $model->diversity_id);
        $model->delete();
        return $this->redirect(['view', 'id' => $model->order_id]);
    }

    public function actionUpdate_order_item($id, $field, $value)
    {
        $model = OrderItem::findOne($id);
        $product = Product::findOne($model->product_id);
        if($product && $model->$field != $value){
            if($field == 'quantity') {
                $model->price = $product->getPrice($value, true, $model->diversity_id);
                if ($model->quantity > $value)
                    $product->minusCount($value - $model->quantity, $model->order_id, $model->diversity_id);
                else
                    $product->plusCount($model->quantity - $value, $model->order_id, $model->diversity_id);
            }
        }
        $model->$field = $value;
        $model->save();

        return $this->redirect(['view', 'id' => $model->order_id]);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGet_diversity()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $item_id = $parents[0];
                $out = ProductDiversity::getDiversityDDArray($item_id);
                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGet_payment_url(){
        $get = Yii::$app->request->get();
        $order = Order::findOne($get['id']);
        return Json::encode(['url'=>$order->payment()]);
    }

}
