<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\User;
use Yii;
use yii\filters\AccessControl;

class CartController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['history', 'historyItem'],
                'rules' => [
                    ['allow' => true, 'actions' => ['history', 'historyItem'], 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionAdd($id, $quantity = 1)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->put($product, $quantity);
            return $this->goBack();
        }
    }

    public function actionUpdate_cart_qty()
    {
        $get = Yii::$app->request->get();
        if($get && isset($get['id']) && isset($get['quantity'])) {
            $this->updateQty($get['id'], $get['quantity']);
            $cart = \Yii::$app->cart;

            $product = $cart->getPositionById($get['id']);
            $total = $cart->getCost();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'id' => $get['id'],
                'productTotal' => $product->getCost(),
                'total' => $total,
            ];
        } else {
            return false;
        }

    }

    public function actionList()
    {
        $cart = \Yii::$app->cart;

        $products = $cart->getPositions();
        $total = $cart->getCost();

        return $this->render('list', [
            'products' => $products,
            'total' => $total,
        ]);
    }

    public function actionRemove($id)
    {
        $this->removeItemFromCart($id);
        $this->redirect(['cart/list']);
    }

    public function removeItemFromCart($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->remove($product);
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
        $get = Yii::$app->request->get();
        $ajax = false;
        if($get && isset($get['id'])) {
            $ajax = true;
            $this->removeItemFromCart($get['id']);
        }

        $order = new Order();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        /* @var $products Product[] */
        $products = $cart->getPositions();
        $total = $cart->getCost();

        if($products) {
            if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
                $transaction = $order->getDb()->beginTransaction();
                if (!Yii::$app->user->isGuest) {
                    $order->user_id = Yii::$app->user->id;
                    $order->id = date('ymdB');
                }
                $order->save(false);

                foreach ($products as $product) {
                    if ($product->getIsActive() && $product->getIsInStock()) {
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
                        } else {
                            $product->minusCount($orderItem->quantity);
                        }
                    }
                }
                $transaction->commit();
                \Yii::$app->cart->removeAll();

                if (!Yii::$app->user->isGuest) {
                    $user = User::findOne(Yii::$app->user->id);
                    if($order->fio) $user->fio = $order->fio;
                    if($order->address) $user->address = $order->address;
                    if($order->phone) $user->phone = $order->phone;
                    $user->save(false);
                }

                \Yii::$app->session->addFlash('success', 'Спасибо за заказ. Мы свяжемся с Вами в ближайшее время.');
                $order->sendEmail();

                return $this->redirect('/catalog');
            }
            if(!Yii::$app->user->isGuest) {
                $order->fio = Yii::$app->user->getIdentity()->fio;
                $order->address = Yii::$app->user->getIdentity()->address;
                $order->phone = Yii::$app->user->getIdentity()->phone;
                $order->email = Yii::$app->user->getIdentity()->email;
            }
            if($ajax){
                return $this->renderPartial('order', [
                    'order' => $order,
                    'products' => $products,
                    'total' => $total,
                ]);
            } else {
                return $this->render('order', [
                    'order' => $order,
                    'products' => $products,
                    'total' => $total,
                ]);
            }
        } else {
            if($ajax){
                return false;
            } else
            $this->redirect('/cart/list');
        }
    }

    public function actionHistory(){
        $history = Order::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->all();
        return $this->render('history', [
            'history' => $history,
        ]);
    }

    public function actionHistory_item($orderId){
        $order = Order::findOne($orderId);
        return $this->render('history_item', [
            'order' => $order,
        ]);
    }
}

