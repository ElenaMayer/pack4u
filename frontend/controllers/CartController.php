<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\ProductDiversity;
use common\models\ProductNotification;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use frontend\components\GeoBehavior;

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
            'geoBehavior' => GeoBehavior::className(),
        ];
    }

    public function actionAdd($id, $diversity_id = null, $quantity = 1)
    {
        $product = Product::findOne($id);
        if ($product) {
            $position = $product->getCartPosition();
            if ($diversity_id)
                $position->diversity_id = $diversity_id;
            $cart = \Yii::$app->cart;
            $cart->put($position, $quantity);
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
            $cart = \Yii::$app->cart;
            $position = $cart->getPositionById($get['id']);

            if ($position) {
                $product = $position->getProduct();
                if($product->getItemCount($position->diversity_id) > $get['quantity']){
                    $count = $get['quantity'];
                } else {
                    $count = $product->getItemCount($position->diversity_id);
                }
                $this->updateQty($get['id'], $count);
            }

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
        $cart = \Yii::$app->cart;
        $cart->removeById($id);
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['data' => $this->renderPartial('_total', [
            'orderAvailable' => $cart->getCost() >= Yii::$app->params['orderMinSum'],
            'subtotal' => $cart->getCost(),
            'total' => $cart->getCost(true),
            'discount' => $cart->getDiscount(),
            'discountPercent' => $cart->getDiscountPercent(),
        ])];
    }

    public function actionUpdate($id, $quantity)
    {
        $this->updateQty($id, $quantity);
        $this->redirect(['cart/list']);
    }

    public function updateQty($id, $quantity)
    {
        $cart = \Yii::$app->cart;
        $position = $cart->getPositionById($id);
        if ($position) {
            \Yii::$app->cart->update($position, $quantity);
        }
    }

    public function actionOrder()
    {
        $order = new Order();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        $total = $cart->getCost();
        $shippingMethod = 'tk';

        if($total < Yii::$app->params['orderMinSum']){
            $this->redirect(['cart']);
        }

        $positions = $cart->getPositions();
        if($positions) {
            if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
                if($total < Yii::$app->params['orderMinSum']){
                    $this->redirect(['cart']);
                } elseif ($redirectUrl = $this->processOrder($order, $cart, $positions)){
                    return $this->redirect($redirectUrl);
                }
            }
            $get = Yii::$app->request->get();
            if($get) {
                $order->fio = isset($get['fio'])?$get['fio']:'';
                $order->address = isset($get['address'])?$get['address']:'';
                $order->phone = isset($get['phone'])?$get['phone']:'';
                $order->email = isset($get['email'])?$get['email']:'';
                if($get['sm']){
                    $shippingMethod = $get['sm'];
                    if($shippingMethod == 'rp'){
                        $order->shipping_cost = Yii::$app->params['shippingCostDefault'];
                    }
                }
            } elseif(!Yii::$app->user->isGuest) {
                $order->fio = Yii::$app->user->getIdentity()->fio;
                $order->address = Yii::$app->user->getIdentity()->address;
                $order->phone = Yii::$app->user->getIdentity()->phone;
                $order->email = Yii::$app->user->getIdentity()->email;
            }
            $order->payment_method = 'online';
            return $this->render('order', [
                'order' => $order,
                'positions' => $positions,
                'cart' => $cart,
                'shippingMethod' => $shippingMethod,
            ]);
        } else {
                $this->redirect('/cart');
        }
    }

    public function processOrder(Order $order, $cart, $positions){
        $transaction = $order->getDb()->beginTransaction();
        if (!Yii::$app->user->isGuest) {
            $order->user_id = Yii::$app->user->id;
            //$order->discount = $cart->getDiscountPercent();

            $user = User::findOne(Yii::$app->user->id);
            if(!$user->fio) $user->fio = $order->fio;
            if(!$user->address) $user->address = $order->address;
            if(!$user->phone) $user->phone = $order->phone;
            $user->save(false);
        }

        if(!$order->shipping_cost) $order->shipping_cost = Order::getShippingCost($order->shipping_method);
        $cookies = Yii::$app->request->cookies;
        $location = $cookies->getValue('location');
        if($location) {
            if($order->city)
                $order->city = $location . ', ' . $order->city;
            else
                $order->city = $location;
        }

        $order->save(false);
        Yii::debug('Заказ #' . $order->id . ' создан ->', 'order');

        foreach ($positions as $position) {
            $product = $position->getProduct();
            if ($product->getIsActive() && $product->getIsInStock()) {
                $qty = $position->getQuantity();
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->title = $product->title;
                if($position->diversity_id){
                    $orderItem->diversity_id = $position->diversity_id;
                    $diversity = ProductDiversity::findOne($position->diversity_id);
                    $orderItem->title .= ' "' . $diversity->title . '"';
                    if($product->getItemCount($position->diversity_id) < $qty)
                        $qty = $product->getItemCount($position->diversity_id);
                }
                $orderItem->price = $product->getPrice($qty);
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $qty;
                $orderItem->save(false);
                if(!$orderItem->diversity_id){
                    Yii::debug( 'Арт.' . $orderItem->product->article . ' ' . $orderItem->product->count . ' -> ' . ($orderItem->product->count-$orderItem->quantity) . 'шт', 'order');
                } else {
                    Yii::debug('Расцветка Арт.' . $orderItem->diversity->article . ' ' . $orderItem->diversity->count . ' -> ' . ($orderItem->diversity->count-$orderItem->quantity) . 'шт', 'order');
                }
                $product->minusCount($orderItem->quantity, $position->diversity_id);
            }
        }
        $transaction->commit();

        Yii::$app->cart->removeAll();

        $order->sendOrderEmail();
        if($order->payment_method == 'online'){
            return $order->payment();
        } else {
            return "/cart/complete?id=$order->id";
        }
    }

    public function actionComplete($id){
        $order = Order::findOne($id);

        if($order->payment_method == 'online'){
            $order->checkPayment();

            if($order->payment == 'canceled'){
                $paymentUrl = $order->payment();
            }
        }

        return $this->render('complete', [
            'order' => $order,
            'paymentUrl' => isset($paymentUrl) ? $paymentUrl : false,
        ]);
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

    public function actionGet_shipping($shipping_method)
    {
        $cart = \Yii::$app->cart;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->renderPartial('_total', [
            'subtotal' => $cart->getCost(),
            'total' => $cart->getCost(true),
            'discount' => $cart->getDiscount(),
            'discountPercent' => $cart->getDiscountPercent(),
            'shippingMethod' => $shipping_method,
            'shippingCost' => Order::getShippingCost($shipping_method),
        ]);
    }

    public function actionAdd_notification(){
        $phone = Yii::$app->user->isGuest ? Yii::$app->request->post('phone') : Yii::$app->user->getIdentity()->phone;
        $userId = Yii::$app->user->isGuest ? 0 : Yii::$app->user->id;

        if(!$userId){
            $user = User::findOne(['phone' => $phone]);
            if($user){
                $userId = $user->id;
            }
        }
        $productId = Yii::$app->request->post('id');
        $diversityId = Yii::$app->request->post('diversity_id');

        if($phone && $productId){
            $notification = new ProductNotification();
            $notification->user_id = $userId;
            $notification->phone = $phone;
            $notification->product_id = $productId;
            $notification->diversity_id = $diversityId;
            $notification->is_active = 1;
            if($notification->save()){
                ProductNotification::addNotificationToCookie($productId, $diversityId);
                return true;
            }
        }
        return false;
    }

}

