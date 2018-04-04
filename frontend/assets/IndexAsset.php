<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use frontend\assets\AppAsset;

class IndexAsset extends AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];
    public $css = [
        'css/owl.carousel.css',
        'css/settings.css',
    ];
    public $js = [
//        'js/jquery.plugin.min.js',
//        'js/jquery.countdown.min.js',
        'js/jquery.themepunch.tools.min.js',
        'js/jquery.themepunch.revolution.min.js',
//        'js/extensions/revolution.extension.video.min.js',
//        'js/extensions/revolution.extension.slideanims.min.js',
//        'js/extensions/revolution.extension.actions.min.js',
//        'js/extensions/revolution.extension.layeranimation.min.js',
//        'js/extensions/revolution.extension.kenburn.min.js',
//        'js/extensions/revolution.extension.navigation.min.js',
//        'js/extensions/revolution.extension.migration.min.js',
//        'js/extensions/revolution.extension.parallax.min.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}