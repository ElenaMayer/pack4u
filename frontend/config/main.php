<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'cart' => [
            'class' => 'frontend\models\MyShoppingCart',
        ],
        'request' => [
            'baseUrl' => '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'catalog' => 'catalog/list',
                'catalog/sale' => 'catalog/sale',
                'catalog/<categorySlug:\w+>' => 'catalog/list',
                'catalog/<categorySlug:\w+>/<productId:\d+>' => 'catalog/product',
                'cart' => 'cart/list',
                'wishlist' => 'wishlist/list',
                'contact' => 'site/contact',
                'shipping' => 'site/shipping',
                'payment' => 'site/payment',
                'refund' => 'site/refund',
                'offer' => 'site/offer',
                'history' => 'cart/history',
                'history/<orderId:\d+>' => 'cart/history_item',
                'search' => 'site/search',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views' => '@app/views/user'
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [
                        'jquery.min.js'
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [
                        'css/bootstrap.min.css'
                    ]
                ]
            ]
        ]
    ],
    'params' => $params,
];
