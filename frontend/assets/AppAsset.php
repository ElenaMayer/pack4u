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
        'position' => \yii\web\View::POS_END
    );
    public $css = [
        'css/site.css',
        'css/bootstrap.min.css',
        'css/owl.carousel.css',
        'css/owl.theme.css',
        'css/font-awesome.min.css',
        '//fonts.googleapis.com/css?family=Lato:100,300,400,700,900,300italic,400italic,700italic,900italic',
        '//fonts.googleapis.com/css?family=Pacifico:100,300,400,700,900,300italic,400italic,700italic,900italic',
        '//fonts.googleapis.com/css?family=Anonymous+Pro:',
        "//fonts.googleapis.com/css?family=Roboto:700",
        'css/style.css?7',
        'css/custom.css?1',
        'css/magnific-popup.css',

        // product
        'css/prettyPhoto.css',

        //index
        'css/settings.css'
    ];
    public $js = [
        'js/jquery.min.js',
        'js/bootstrap.min.js',
        'js/jquery-migrate.min.js',
        'js/modernizr-2.7.1.min.js',
        'js/off-cavnass.js',
        'js/script.js',
        'js/custom.js?3',
        'js/imagesloaded.pkgd.min.js',
        'js/isotope.pkgd.min.js',
        'js/portfolio.js',
        'js/jquery.touchSwipe.min.js',
        'js/jquery.carouFredSel-6.2.1.js',
        'js/jquery.isotope.min.js',
        'js/owl.carousel.min.js',
        'js/jquery.magnific-popup.js',
        'js/modernizr.custom.js',
        'js/jquery.prettyPhoto.js?1',
        'js/jquery.prettyPhoto.init.min.js',
        'js/draggabilly.pkgd.min.js',
        'js/elastiStack.js',
        'js/core.min.js',
        'js/widget.min.js',
        'js/mouse.min.js',
        'js/slider.min.js',
        'js/jquery.ui.touch-punch.js',
        'js/price-slider.js',
        'js/instafeed.min.js',


        //index
        'js/jquery.plugin.min.js',
        'js/jquery.countdown.min.js',
        'js/jquery.themepunch.tools.min.js',
        'js/jquery.themepunch.revolution.min.js',
        'js/extensions/revolution.extension.video.min.js',
        'js/extensions/revolution.extension.slideanims.min.js',
        'js/extensions/revolution.extension.actions.min.js',
        'js/extensions/revolution.extension.layeranimation.min.js',
        'js/extensions/revolution.extension.kenburn.min.js',
        'js/extensions/revolution.extension.navigation.min.js',
        'js/extensions/revolution.extension.migration.min.js',
        'js/extensions/revolution.extension.parallax.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];


}
