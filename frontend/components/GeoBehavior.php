<?php

namespace frontend\components;

use yii\base\Behavior;
use yii\web\Controller;
use frontend\models\Dadata;
use Yii;
use yii\web\Cookie;

class GeoBehavior extends Behavior
{

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'geoLocation'
        ];
    }

    public function geoLocation(){
        $location = Yii::$app->request->cookies->get('location');
        if(!$location) {
            $location = 'Новосибирск';
            $zipcode = 0;
            $dadata = new Dadata(Yii::$app->params['dadata_key']);
            $dadata->init();
//            $result = $dadata->iplocate('37.192.125.53');
            $result = $dadata->iplocate($_SERVER['REMOTE_ADDR']);
            if (!empty($result['location'])){
                if(isset($result['location']['data']['city'])) {
                    $location = $result['location']['data']['city'];
                }
                if(isset($result['location']['data']['postal_code'])) {
                    $zipcode = $result['location']['data']['postal_code'];
                }
            }
            $dadata->close();
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'location',
                'value' => $location,
            ]));
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'zipcode',
                'value' => $zipcode,
            ]));
        }
    }
}