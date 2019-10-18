<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END,
        'async' => true,
        'defer' => true
    );
    public $css = [
        'css/font-awesome.min.css',
        'css/custom.min.css?2',
        'css/style.css?115',
//        'css/colors/apple-green.css?12',
    ];
    public $js = [
        'js/off-cavnass.min.js',
        "js/swiper.min.js",
        'js/owl.carousel.min.js?1',
        'js/custom.js?59',
        'js/instafeed.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];


}
