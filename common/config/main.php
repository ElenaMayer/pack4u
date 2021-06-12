<?php
return [
    'language' => 'ru',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'Asia/Novosibirsk',
        ],
        'logger' => [
            'class' => 'common\components\Logger',
        ],
        'cdek' => [
            'class' => 'common\components\Cdek',
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'modelMap' => [
                'User' => 'common\models\User',
            ],
            'controllerMap' => [
                'registration' => 'frontend\controllers\user\RegistrationController',
                'settings' => 'frontend\controllers\user\SettingsController'
            ],
            'admins' => ['admin'],
            'mailer' => [
                'sender'                => ['support@pack4u.ru' => 'pack4u.ru'], // or ['no-reply@myhost.com' => 'Sender name']
                'welcomeSubject'        => 'Спасибо за регистрацию!',
                'confirmationSubject'   => 'Подтверждение учетной записи',
                'reconfirmationSubject' => 'Изменение Email',
                'recoverySubject'       => 'Восстановление пароля',
            ],
        ],
    ],
];
