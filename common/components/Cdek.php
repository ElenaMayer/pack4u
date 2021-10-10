<?php

namespace common\components;

use yii\httpclient\Client;
use Yii;

class Cdek
{

    private function auth()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl(Yii::$app->params['cdek_url'] . 'oauth/token?parameters')
            ->setData([
                'grant_type' => 'client_credentials',
                'client_id' => Yii::$app->params['cdek_client_id'],
                'client_secret' => Yii::$app->params['cdek_client_secret'],
            ])
            ->send();
        return $response->data;
    }

    private function send($method, $data)
    {
        if(!$auth = $this->auth())
            return false;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            ->setUrl(Yii::$app->params['cdek_url'] . $method)
            ->setData($data)
            ->setHeaders(['Authorization' => 'Bearer '.$auth['access_token']])
            ->send();

        if ($response->isOk) {
            return $response->data;
        } else {
            return false;
        }
    }

    public function getPvz($zipcode)
    {
        $result = [];
        $responce = $this->send('deliverypoints', ['postal_code' => $zipcode]);
        foreach ($responce as $item){
            $result[$item['code'] . ', ' . $item['location']['address']] = $item['name'] . ', ' . $item['location']['address'];
        }
        return $result;
    }
}