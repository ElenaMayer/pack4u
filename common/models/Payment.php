<?php

namespace common\models;

use Yii;
use YandexCheckout\Client;

class Payment extends Client
{
    public function auth(){
        $this->setAuth(Yii::$app->params['yk_shop_id'], Yii::$app->params['yk_secret_key']);
    }

    public static function getErrorDesc($error){
        $errors = [
            '3d_secure_failed' => 'Не пройдена аутентификация',
            'call_issuer' => 'Причина неизвестна',
            'card_expired' => 'Истек срок действия банковской карты',
            'country_forbidden' => 'Нельзя заплатить банковской картой, выпущенной в этой стране',
            'fraud_suspected' => 'Платеж заблокирован из-за подозрения в мошенничестве',
            'general_decline' => 'Причина не детализирована',
            'identification_required' => 'Превышены ограничения на платежи для кошелька в Яндекс.Деньгах',
            'insufficient_funds' => 'Не хватает денег для оплаты',
            'invalid_card_number' => 'Неправильно указан номер карты',
            'invalid_csc' => 'Неправильно указан код CVV2 (CVC2, CID)',
            'issuer_unavailable' => 'Организация, выпустившая платежное средство, недоступна',
            'payment_method_limit_exceeded' => 'Исчерпан лимит платежей для данного платежного средства',
            'payment_method_restricted' => 'Запрещены операции данным платежным средством',
            'permission_revoked' => 'Нельзя провести безакцептное списание',
        ];
        return $errors[$error];
    }

    public function payment($order){

        $items = [];
        foreach ($order->orderItems as $item){
            $items[] = [
                "description" => $item->title,
                "quantity" => $item->quantity,
                "amount" => [
                    "value" => $item->price,
                    "currency" => 'RUB'
                ],
                "vat_code" => '1',
                "payment_mode" => 'full_prepayment',
                "payment_subject" => 'commodity',
            ];
        }

        $this->auth();
        return $this->createPayment([
            'amount' => [
                'value' => $order->getCost(),
                'currency' => 'RUB',
            ],
            'payment_method_data' => [
                'type' => 'bank_card',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => 'https://'.Yii::$app->params['domain'].'/cart/complete?id='.$order->id,
            ],
            'receipt' => [
                [
                    'items' => $items,
                ]
            ],
            'capture' => true,
            'description' => "Заказ №$order->id",
        ],
            uniqid('', true)
        );
    }

    public function checkPayment($id){

        $this->auth();
        return $this->getPaymentInfo($id);
    }
}