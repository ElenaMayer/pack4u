<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use Yii;
use frontend\models\MyShoppingCart;

class CartController extends \yii\web\Controller
{
    public function actionAdd($id, $quantity = 1)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->put($product, $quantity);
            return $this->goBack();
        }
    }

    public function actionList()
    {
        $get = Yii::$app->request->get();
        $ajax = false;
        if($get && isset($get['id']) && isset($get['quantity'])) {
            $ajax = true;
            $this->updateQty($get['id'], $get['quantity']);
        }

        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;

        $products = $cart->getPositions();
        $total = $cart->getCost();

        if($ajax){
            return $this->renderPartial('list', [
                'products' => $products,
                'total' => $total,
            ]);
        } else {
            return $this->render('list', [
                'products' => $products,
                'total' => $total,
            ]);
        }
    }

    public function actionRemove($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->remove($product);
            $this->redirect(['cart/list']);
        }
    }

    public function actionUpdate($id, $quantity)
    {
        $this->updateQty($id, $quantity);
        $this->redirect(['cart/list']);
    }

    public function updateQty($id, $quantity)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->update($product, $quantity);
        }
    }

    public function actionOrder()
    {
        $order = new Order();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        /* @var $products Product[] */
        $products = $cart->getPositions();
        $total = $cart->getCost();

        if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
            $transaction = $order->getDb()->beginTransaction();
            $order->save(false);

            foreach($products as $product) {
                if($product->getIsActive() && $product->getIsInStock()) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->title = $product->title;
                    $orderItem->price = $product->getPrice();
                    $orderItem->product_id = $product->id;
                    $orderItem->quantity = $product->getQuantity();
                    if (!$orderItem->save(false)) {
                        $transaction->rollBack();
                        \Yii::$app->session->addFlash('error', 'Невозможно создать заказ. Пожалуйста свяжитесь с нами.');
                        return $this->redirect('/catalog');
                    }
                }
            }
            $transaction->commit();
            \Yii::$app->cart->removeAll();

            \Yii::$app->session->addFlash('success', 'Спасибо за заказ. Мы свяжемся с Вами в ближайшее время.');
            $order->sendEmail();

            return $this->redirect('/catalog');
        }
        return $this->render('order', [
            'order' => $order,
            'products' => $products,
            'total' => $total,
        ]);
    }
}

