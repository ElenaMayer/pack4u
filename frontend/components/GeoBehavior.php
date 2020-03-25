<?php

namespace frontend\components;

use yii\base\Behavior;
use yii\web\Controller;
use frontend\models\Dadata;
use Yii;

class GeoBehavior extends Behavior
{

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'geoLocation'
        ];
    }

    public function geoLocation(){

        $cache = Yii::$app->cache;

        $location = $cache->get('location');
        if(!$location) {
            $dadata = new Dadata(Yii::$app->params['dadata_key']);
            $dadata->init();

            $result = $dadata->iplocate('37.192.125.53');
            //        $result = $dadata->iplocate(Yii::$app->request->userIP);

            if (!empty($result['location']) && isset($result['location']['data']['city'])) {
                $location = $result['location']['data']['city'];
            } else {
                $location = 'Новосибирск';
            }
            $dadata->close();
            $cache->set('location', $location);
        }
    }
}