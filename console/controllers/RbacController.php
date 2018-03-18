<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        $updatePost = $auth->createPermission('admin');
        $updatePost->description = 'Admin';
        $auth->add($updatePost);

        $admin = $auth->createRole('adminRole');
        $auth->add($admin);
        $auth->addChild($admin, $updatePost);

        $auth->assign($admin, 1);
        $auth->assign($admin, 3);
    }
}