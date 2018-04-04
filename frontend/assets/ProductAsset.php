<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use frontend\assets\AppAsset;

class ProductAsset extends AssetBundle
{
    public $css = [
        'css/prettyPhoto.css',
        "css/swiper.css",
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}