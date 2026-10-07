<?php

use common\components\I18n;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var frontend\models\StudentLoginForm $model */

?>

<div class="login-visual"></div>

<section class="login-panel"><a class="brand-login" href="/<?= Yii::$app->globalData->lang ?>">
        <img src="/images/logo_am.svg" alt="ՀՊՏՀ"></a>
    <div class="eyebrow red"><?= I18n::translate('personal_system') ?></div>
    <h1><?= I18n::translate('user_login') ?></h1>
    <p class="body-copy"><?= I18n::translate('log_in_to_your_centralized_digital_environment') ?></p>
    <?php $form = ActiveForm::begin([
            'options' => [
                    'class' => 'login-form',
            ],
            'fieldConfig' => [
                    'template' => "{input}\n{hint}\n{error}",
                    'options' => [
                            'class' => 'field-wrapper',
                    ],
            ]
    ]); ?>

    <label class="field">Օգտվողի տեսակ

        <?= $form->field($model, 'usertype')->dropDownList(\common\components\UserRoles::getLoginUserTypeOptions())->label(false) ?>

    </label>

    <div class="field">
        <label><?= I18n::translate('email_username') ?></label>
        <?= $form->field($model, 'email')->textInput(['id' => 'email'])->label(false) ?>
    </div>
    <div class="field">
        <label><?= I18n::translate('password') ?></label>
        <?= $form->field($model, 'password')->textInput(['id' => 'password', 'type' => 'password'])->label(false) ?>
    </div>

    <?= Html::submitButton(I18n::translate('Մուտք գործել') . '→', ['class' => 'btn-red']) ?>
    <a href="/<?= Yii::$app->globalData->lang ?>/forgot-password" style="font-size:12px;color:#8e1728"><?= I18n::translate('forgot_your_password') ?></a></form>

    <?php ActiveForm::end(); ?>
</section>