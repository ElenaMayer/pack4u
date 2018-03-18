<?php

namespace common\models;

use Yii;
use yii\helpers\Url;


class StaticFunction
{
    public static function addGetParamToCurrentUrl($paramKey, $paramValue){
        $currentUrl = Url::current();
        if(count($get = Yii::$app->request->get())>1) {
            if(isset($get[$paramKey])){
                $get[$paramKey] = $paramValue;
                return Yii::$app->urlManager->createUrl(array_merge([0=>Yii::$app->request->getPathInfo()], $get));
            } else {
                return $currentUrl . '&' . $paramKey . '=' . $paramValue;
            }
        } else {
            return $currentUrl . '?' . $paramKey . '=' . $paramValue;
        }
    }
}