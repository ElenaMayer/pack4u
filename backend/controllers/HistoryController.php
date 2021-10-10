<?php
/**
 * Created by PhpStorm.
 * User: elenam
 * Date: 08.11.2019
 * Time: 13:11
 */

namespace backend\controllers;

use common\models\ProductHistory;
use common\models\OrderItem;
use common\models\Product;
use yii\web\Controller;
use yii\data\ActiveDataProvider;

class HistoryController extends Controller
{
    public function actionIndex($id)
    {
        $model = Product::findOne($id);
        $history = ProductHistory::find($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $history,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $history->andFilterWhere([
            'product_id' => $id,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }

    public function actionOrder($id)
    {
        $query = OrderItem::find();
        $model = Product::findOne($id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->andFilterWhere([
            'product_id' => $id,
        ]);

        return $this->render('order', [
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
}