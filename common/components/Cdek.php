<?php

namespace common\components;

use yii\httpclient\Client;

class Cdek
{
    private $url = 'https://api.edu.cdek.ru/v2/';

    private function auth()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('post')
            ->setUrl($this->url . 'oauth/token?parameters')
            ->setData([
                'grant_type' => 'client_credentials',
                'client_id' => 'EMscd6r9JnFiQ3bLoyjJY6eM78JrJceI',
                'client_secret' => 'PjLZkKBHEiLK3YsjtNrt3TGNG0ahs3kG',
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
            ->setUrl($this->url . $method)
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