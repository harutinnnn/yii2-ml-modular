<?php

use common\components\I18n;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var frontend\models\StudentLoginForm $model */

?>

<div class="login-visual"></div>

<section class="login-panel"><a class="brand-login" href="/<?= Yii::$app->globalData->lang ?>">
        <img src="/images/logo_am.svg" alt="ՀՊՏՀ"></a>
    <div class="eyebrow red">Անձնական համակարգ</div>
    <h1>Վերականգնել գաղտնաբառը</h1>
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
        <label>Էլ. փոստ / օգտանուն</label>
        <?= $form->field($model, 'email')->textInput(['id' => 'email'])->label(false) ?>
    </div>


    <?= Html::submitButton(I18n::translate('Վերականգնել') . '→', ['class' => 'btn-red']) ?>
    <a href="/<?= Yii::$app->globalData->lang ?>/forgot-password" style="font-size:12px;color:#8e1728">Մուտք գործել</a></form>

    <?php ActiveForm::end(); ?>
</section>