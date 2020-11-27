<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use frontend\assets\AppAsset;

class IndexAsset extends AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
        'defer' => 'defer'
    ];
    public $css = [
        'css/owl.carousel.min.css',
        'css/settings.min.css?3',
    ];
    public $js = [
        'js/jquery.themepunch.tools.min.js',
        'js/jquery.themepunch.revolution.min.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}