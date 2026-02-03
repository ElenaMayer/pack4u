<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $model
 * @var dektrium\user\Module $module
 */

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <?= Html::encode($this->title) ?>
                    <span>&nbsp;/&nbsp;</span>
                    <?= Html::a(Yii::t('user', 'Войти'), ['/user/security/login']) ?>
                </h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>

                <?php if ($module->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <div class="form-group field-offer required">
                    <input type="checkbox" id="offer-checkbox" name="offer" value="1" class="offer-checkbox-input" aria-required="true">
                    <span class="offer-checkbox-text">
                        Я принимаю условия
                        <?= Html::a('публичной оферты', ['/site/offer'], [
                                'target' => '_blank',
                                'rel' => 'noopener noreferrer',
                                'class' => 'offer-link'
                        ]) ?>
                        <span class="required-mark" aria-hidden="true">*</span>
                    </span>
                    <div id="offer-error" class="help-block help-block-error offer-error"
                         role="alert" aria-live="assertive"></div>
                </div>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), [
                        'class' => 'btn btn-success btn-block registration-submit-btn',
                        'id' => 'register-button',
                        'disabled' => true,
                        'aria-disabled' => 'true'
                ]) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>