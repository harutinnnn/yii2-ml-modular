<?php

use common\components\I18n;
use common\models\TranslationModel;
use yii\widgets\ActiveForm;

$this->params['hideMainHeader'] = true;
$this->params['topHeader'] = true;
$this->registerCssFile("/css/spec-modules.css");


?>

<header class="module-header"><a href="event-detail.html">← Միջոցառմանը</a>
    <nav>
        <a href="events.html">Օրացույց</a>
        <a href="notifications.html">Ծանուցումներ</a>
    </nav>

    <span>HY · EN · RU</span>
</header>

<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow">
                <?= TranslationModel::getTranslation($event, Yii::$app->globalData->lang) ?>
            </div>
            <h1><?= I18n::translate('registration_for_participation') ?></h1>
            <p>
                <?= date('d.m.Y', strtotime($event->date)) ?> · <?= substr($event->time,0,5) ?> · <?= TranslationModel::getTranslation($event, Yii::$app->globalData->lang,'place') ?>
            </p>
        </div>
        <div class="status-box">
            <strong>34 տեղ</strong>
            <span>
                <?= I18n::translate('registration_closes') ?>
                <?= date('d.m.Y H:i', strtotime($event->registration_close_date)) ?>
            </span>
        </div>
    </section>

    <section class="module-section">
        <?php $form = ActiveForm::begin([
                'options' => [
                        'class' => '',
                ],
                'fieldConfig' => [
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                                'class' => 'field-wrapper',
                        ],
                ]
        ]); ?>

        <div class="form-grid">

            <div class="d-none">
                <?= $form->field($model, 'event_id')->hiddenInput()->label(false) ?>
            </div>
            <label class="field">
                Անուն
                <?= $form->field($model, 'first_name')->textInput()->label(false) ?>
            </label>

            <label class="field">Ազգանուն
                <?= $form->field($model, 'last_name')->textInput()->label(false) ?>
            </label>

            <label class="field">Էլ. փոստ
                <?= $form->field($model, 'email')->textInput()->label(false) ?>
            </label>

            <label class="field">
                Հեռախոս
                <?= $form->field($model, 'phone')->textInput()->label(false) ?>
            </label>

        </div>
        <label>
            <?=
            $form->field(
                    $model, 'consent_to_the_processing_of_my_personal_data')
                    ->checkbox(
                            [
                                    'label' => I18n::translate('consent_to_the_processing_of_my_personal_data')
                            ]
                    )
            ?>
        </label>
        <p>
            <button class="btn" type="submit"><?= I18n::translate('register') ?></button>
        </p>
        <?php if (Yii::$app->session->hasFlash('success_registered')): ?>
            <div class="notice">
                <?= I18n::translate('event_registration_success_message') ?>
            </div>
        <?php endif; ?>

        <?php ActiveForm::end(); ?>
    </section>
</main>