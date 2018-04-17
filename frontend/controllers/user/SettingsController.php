<?php

namespace frontend\controllers\user;

use common\models\User;
use dektrium\user\controllers\SettingsController as BaseSettingsController;

class SettingsController extends BaseSettingsController
{
    public function actionProfile()
    {
        $model = User::findOne(\Yii::$app->user->identity->getId());

        if ($model == null) {
            $model = \Yii::createObject(User::className());
            $model->link('user', \Yii::$app->user->identity);
        }

        $this->performAjaxValidation($model);
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('success', \Yii::t('user', 'Your profile has been updated'));

            return $this->refresh();
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}