<?php
namespace frontend\assets;

use yii\web\AssetBundle;
use frontend\assets\AppAsset;

class ContactAsset extends AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_END,
        'defer' => 'defer'
    ];
    public $js = [
        'https://maps.googleapis.com/maps/api/js?key=AIzaSyAEJgte17bKvMyyWXo1JcWbzsl9Qy-3-uo',
        'js/google-map-custom.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}