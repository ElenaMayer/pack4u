<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use frontend\assets\AppAsset;

class CatalogAsset extends AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
        'async' => 'async',
        'defer' => 'defer'
    ];
    public $css = [
    ];
    public $js = [
        'js/price-slider.js?1',
        'js/widget.min.js',
        'js/mouse.min.js',
        'js/slider.min.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}