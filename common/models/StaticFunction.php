<?php

namespace common\models;

use Yii;


class StaticFunction
{
    public static function addGetParamToCurrentUrl($paramKey, $paramValue, $currentUrl = null){
        if(!$currentUrl) $currentUrl = Yii::$app->request->url;
        if (strripos($currentUrl, '?') === false) {
            return $currentUrl . '?' . $paramKey . '=' . $paramValue;
        } else {
            $urlArr = explode('?', $currentUrl);
            $getArr = explode('&', $urlArr[1]);
            foreach ($getArr as $key => $get){
                if (strripos($get, $paramKey) !== false) {
                    $getArr[$key] = preg_replace('/=.+$/i', '='.$paramValue, $get);
                }
                if (strripos($get, 'page') !== false) {
                    unset($getArr[$key]);
                }
            }
            if (strripos($currentUrl, $paramKey) === false) {
                $getArr[] = $paramKey . '=' . $paramValue;
            }
            return $urlArr[0] . '?' . implode('&', $getArr);
        }
    }

    public static function getParamFromCurrentUrl(){
        $currentUrl = Yii::$app->request->url;
        if (strripos($currentUrl, '?') === false) {
            return false;
        } else {
            $urlArr = explode('?', $currentUrl);
            return '?' . $urlArr[1];
        }
    }

    public static function getParamArrayFromCurrentUrl(){
        $currentUrl = Yii::$app->request->url;
        if (strripos($currentUrl, '?') === false) {
            return [];
        } else {
            $res = [];
            $urlArr = explode('?', $currentUrl);
            $urlParams = $urlArr[1];
            $urlParamsArr = explode('&', $urlParams);
            foreach ($urlParamsArr as $urlParamSt) {
                if (strripos($urlParamSt, 'page') === false) {
                    $urlParamArr = explode('=', $urlParamSt);
                    $res[$urlParamArr[0]] = $urlParamArr[1];
                }
            }
            return $res;
        }
    }
}