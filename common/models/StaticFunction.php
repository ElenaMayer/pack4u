<?php

namespace common\models;

use Yii;
use yii\helpers\Url;


class StaticFunction
{
    public static function addGetParamToCurrentUrl($paramKey, $paramValue, $currentUrl = null){
        if(!$currentUrl) $currentUrl = Yii::$app->request->getPathInfo();
        if (strripos($currentUrl, '?') === false) {
            return $currentUrl . '?' . $paramKey . '=' . $paramValue;
        } else {
            if (strripos($currentUrl, $paramKey) === false) {
                return $currentUrl . '&' . $paramKey . '=' . $paramValue;
            } else {
                $urlArr = explode('?', $currentUrl);
                $getArr = explode('&', $urlArr[1]);
                foreach ($getArr as $key => $get){
                    if (strripos($get, $paramKey) !== false) {
                        $getArr[$key] = preg_replace('/=.+$/i', '='.$paramValue, $get);
                    }
                }
                return $urlArr[0] . '?' . implode('&', $getArr);
            }
        }
    }

    public static function getParamFromCurrentUrl(){
        $currentUrl = Yii::$app->request->getPathInfo();
        if (strripos($currentUrl, '?') === false) {
            return false;
        } else {
            $urlArr = explode('?', $currentUrl);
            return '?' . $urlArr[1];
        }
    }
}