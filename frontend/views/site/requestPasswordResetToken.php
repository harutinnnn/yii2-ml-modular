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
    <h1><?= I18n::translate('reset_password') ?></h1>
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

    <div class="field">
        <label><?= I18n::translate('email') ?></label>
        <?= $form->field($model, 'email')->textInput(['id' => 'email'])->label(false) ?>
    </div>


    <?= Html::submitButton(I18n::translate(I18n::translate('restore')) . '→', ['class' => 'btn-red']) ?>
    <a href="/<?= Yii::$app->globalData->lang ?>/forgot-password" style="font-size:12px;color:#8e1728">
        <?= I18n::translate('log_in') ?>
    </a>


    <?php ActiveForm::end(); ?>
</section>