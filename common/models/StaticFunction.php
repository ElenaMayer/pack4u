<?php

namespace common\models;

use Yii;


class StaticFunction
{
    public static function addGetParamToCurrentUrl($paramKey, $paramValue, $multiple = false){
        $currentUrl = Yii::$app->request->url;
        if (strripos($currentUrl, '?') === false) {
            return $currentUrl . '?' . $paramKey . '=' . $paramValue;
        } else {
            $urlArr = explode('?', $currentUrl);
            $getArr = explode('&', $urlArr[1]);
            foreach ($getArr as $key => $get){
                if (strripos($get, 'page') !== false) {
                    unset($getArr[$key]);
                }
                if (strripos($get, $paramKey) !== false) {
                    if(!$multiple || strripos($get, 'all') !== false) {
                        $getArr[$key] = preg_replace('/=.+$/i', '=' . $paramValue, $get);
                    } else {
                        if(strripos(rawurldecode($get), $paramValue) === false) {
                            $getArr[$key] = $get . ';' . $paramValue;
                        } else {
                            $parArr = explode('=', $get);
                            $params = explode(';', $parArr[1]);
                            foreach ($params as $k => $param){
                                if(rawurldecode($param) == $paramValue)
                                    unset($params[$k]);
                            }
                            $getArr[$key] = $parArr[0] . '=' . implode(';', $params);
                        }
                    }
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

    public static function arrayMultiSort($array){
        usort($array, function ($a, $b) {
            $aArr = explode('*', $a);
            $bArr = explode('*', $b);
            array_walk($aArr, function(&$item) { $item = intval($item); });
            array_walk($bArr, function(&$item) { $item = intval($item); });
            return self::arrayMultiDiff($aArr, $bArr);
        });
        return $array;
    }

    private static function arrayMultiDiff($a, $b, $k = 0){
        if($a[$k] && $b[$k]) {
            if ($a[$k] > $b[$k]) {
                return 1;
            } elseif ($a[$k] < $b[$k]) {
                return -1;
            } else {
                return self::arrayMultiDiff($a, $b, $k + 1);
            }
        } else {
            return 0;
        }
    }
}