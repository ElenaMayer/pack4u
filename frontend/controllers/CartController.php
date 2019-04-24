<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;

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
            $cart = \Yii::$app->cart;
            $cart->put($product, $quantity);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'count' => $cart->getCount(),
            ];
        }
    }

    public function actionUpdate_cart_qty()
    {
        $get = Yii::$app->request->get();
        if($get && isset($get['id']) && isset($get['quantity']) && $get['quantity'] > 0) {
            $product = Product::findOne($get['id']);
            if($product->count > $get['quantity']){
                $count = $get['quantity'];
            } else {
                $count = $product->count;
            }
            $this->updateQty($get['id'], $count);
            $cart = \Yii::$app->cart;

            $product = $cart->getPositionById($get['id']);
            $total = $cart->getCost();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return [
                'id' => $get['id'],
                'count' => $count,
                'productTotal' => $product->getCost(),
                'productPrice' => $product->getPrice($count),
                'orderAvailable' => ($total >= Yii::$app->params['orderMinSum']),
                'data' => $this->renderPartial('_total', [
                    'subtotal' => $cart->getCost(),
                    'total' => $cart->getCost(true),
                    'discount' => $cart->getDiscount(),
                    'discountPercent' => $cart->getDiscountPercent(),
                ])
            ];
        } else {
            return false;
        }

    }

    public function actionCart()
    {
        $cart = \Yii::$app->cart;

        return $this->render('list', [
            'products' => $cart->getPositions(),
            'cart' => $cart,
        ]);
    }

    public function actionRemove($id)
    {
        $this->removeItemFromCart($id);
        $cart = \Yii::$app->cart;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['data' => $this->renderPartial('_total', [
            'orderAvailable' => $cart->getCost() >= Yii::$app->params['orderMinSum'],
            'subtotal' => $cart->getCost(),
            'total' => $cart->getCost(true),
            'discount' => $cart->getDiscount(),
            'discountPercent' => $cart->getDiscountPercent(),
        ])];
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

        if($products) {
            if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
                $transaction = $order->getDb()->beginTransaction();
                if (!Yii::$app->user->isGuest) {
                    $order->user_id = Yii::$app->user->id;
                    $order->id = date('ymdB');
                    $order->discount = $cart->getDiscountPercent();
                }
                $order->save(false);
                Yii::debug('Заказ  #' . $order->id . ' создан.', 'order');

                foreach ($products as $product) {
                    if ($product->getIsActive() && $product->getIsInStock()) {
                        $qty = $product->getQuantity();
                        $orderItem = new OrderItem();
                        $orderItem->order_id = $order->id;
                        $orderItem->title = $product->title;
                        $orderItem->price = $product->getPrice($qty);
                        $orderItem->product_id = $product->id;
                        if($product->count < $qty)
                            $qty = $product->count;
                        $orderItem->quantity = $qty;
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
                    'cart' => $cart,
                ]);
            } else {
                return $this->render('order', [
                    'order' => $order,
                    'products' => $products,
                    'cart' => $cart,
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

    public function actionGet_courier_cost($weight, $address)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, Yii::$app->params['dostavistaUrl']);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['X-DV-Auth-Token: ' . Yii::$app->params['dostavistaToken']]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = [
            'total_weight_kg' => $weight,
            'points' => [
                [
                    'address' => Yii::$app->params['dostavistaAddress'],
                ],
                [
                    'address' => 'г Новосибирск ' . $address,
                ],
            ],
        ];

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);

        $result = curl_exec($curl);
        if ($result === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        return Json::decode($result)['order']['payment_amount'];
    }

}

