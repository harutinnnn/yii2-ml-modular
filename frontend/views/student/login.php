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
    <h1>Ուսանողի մուտք</h1>
    <p class="body-copy">Մուտք գործեք ձեր կենտրոնացված թվային միջավայր։</p>
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
    <div class="field">
        <label>Գաղտնաբառ</label>
        <?= $form->field($model, 'password')->textInput(['id' => 'password', 'type' => 'password'])->label(false) ?>
    </div>

    <?= Html::submitButton(I18n::translate('Մուտք գործել') . '→', ['class' => 'btn-red']) ?>
    <a href="/<?= Yii::$app->globalData->lang ?>/forgot-password" style="font-size:12px;color:#8e1728">Մոռացե՞լ եք գաղտնաբառը</a></form>

    <?php ActiveForm::end(); ?>
</section>