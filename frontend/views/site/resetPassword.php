<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\ResetPasswordForm $model */

use common\components\I18n;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-visual"></div>

<section class="login-panel"><a class="brand-login" href="/<?= Yii::$app->globalData->lang ?>">
        <img src="/images/logo_am.svg" alt="ՀՊՏՀ"></a>

    <h1>Please choose your new password:</h1>
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
        <label>Please choose your new password</label>
        <?= $form->field($model, 'password')->passwordInput(['id' => 'password'])->label(false) ?>
    </div>


    <?= Html::submitButton(I18n::translate('Save ') . '→', ['class' => 'btn-red']) ?>

    <?php ActiveForm::end(); ?>
</section>
