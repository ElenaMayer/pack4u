<?php
namespace frontend\assets;

use yii\web\AssetBundle;

//HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries
//WARNING: Respond.js doesn't work if you view the page via file://
class IeAsset extends AssetBundle
{
    public $jsOptions = [
        'condition' => 'lte IE9',
        'position' => \yii\web\View::POS_HEAD
    ];
    public $js = [
        'https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js',
        'https://oss.maxcdn.com/respond/1.4.2/respond.min.js'
    ];
}