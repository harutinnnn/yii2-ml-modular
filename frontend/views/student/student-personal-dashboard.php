<?php

use common\components\I18n;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$facultyTitle = $faculty?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";
$chairTitle = $chair?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";

?>
<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow"><?= I18n::translate('student_personal_account') ?></div>
            <h1>Բարի գալուստ, <?= $user->additional->first_name ?></h1>
            <p><?= I18n::translate('student_centralized_access_to_personal_data') ?></p></div>
        <div class="status-box">
            <strong><?= I18n::translate('current_student') ?></strong>
            <span>
                <?= $facultyTitle ?> · <?= $user->additional->course ?>-<?= $user->additional->course == 1 ? 'ին' : 'րդ' ?> <?= I18n::translate('course') ?> ·  <?= $user->additional->student_id ?>
            </span>
        </div>
    </section>
    <section id="profile" class="module-section">
        <h2><?= I18n::translate('personal_data') ?></h2>


        <?php if (Yii::$app->session->hasFlash('message')): ?>
            <div class="success-message">
                <?= Yii::$app->session->getFlash('message') ?>
            </div>
        <?php endif; ?>
        <?php $form = ActiveForm::begin([
                'options' => [
                        'name' => 'user-data-form',
                ],
                'fieldConfig' => [
                        'template' => "{input}\n{hint}\n{error}",
                        'options' => [
                                'class' => 'field-wrapper',
                        ],
                ]
        ]); ?>

        <div class="form-grid">
            <label class="field"><?= I18n::translate('name') ?>
                <?= $form->field($studentDataModel, 'first_name')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('surname') ?>
                <?= $form->field($studentDataModel, 'last_name')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('dob') ?>
                <?= $form->field($studentDataModel, 'dob')->textInput(['readonly' => 'readonly', 'type' => 'date'])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('passport_details') ?>
                <?= $form->field($studentDataModel, 'passport_details')->textInput([])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('phone') ?>
                <?= $form->field($studentDataModel, 'phone')->textInput([])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('email') ?>
                <?= $form->field($studentDataModel, 'email')->textInput([])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('faculty_specialty') ?>
                <input value="<?= $facultyTitle ?> / <?= $chairTitle ?>" disabled>
            </label>

        </div>
        <?= Html::submitButton(I18n::translate('save_allowed_changes'), ['class' => 'btn', 'value' => 1]) ?>

        <?php ActiveForm::end(); ?>
    </section>
    <section id="card" class="module-section"><h2><?= I18n::translate('electronic_student_id') ?></h2>
        <div class="panel">
            <span class="badge">
                <?= \common\components\StatusList::getStatusLabel($user->status) ?>
            </span>
            <h3><?= $user->additional->first_name ?> <?= $user->additional->last_name ?></h3>
            <p><?= I18n::translate('id') ?>: <?= $user->additional->student_id ?></p>
            <p> <?= $facultyTitle ?> · <?= $chairTitle ?></p>
            <p class="meta">
                <?= I18n::translate('student_the_status_is_updated_automatically') ?>
            </p>
        </div>
    </section>
    <section id="requests" class="module-section">
        <h2><?= I18n::translate('applications_complaints_and_suggestions') ?></h2>
        <div class="module-tabs">
            <button class="btn">Նոր դիմում</button>
            <span class="pill">Տեղեկանք</span>
            <span class="pill">Արձակուրդ</span>
            <span class="pill">Տեղափոխություն</span>
            <span class="pill">IT / Moodle / email</span>
            <span class="pill">Բողոք</span>
            <span class="pill">Առաջարկ</span>
        </div>
        <table class="data-table">
            <thead>
            <tr>
                <th>N</th>
                <th><?= I18n::translate('type') ?></th>
                <th><?= I18n::translate('submitted') ?></th>
                <th><?= I18n::translate('person_in_charge') ?></th>
                <th><?= I18n::translate('status') ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>0245</td>
                <td>Տեղեկանք</td>
                <td>17.08.2026</td>
                <td>Փաստաթղթաշրջանառություն</td>
                <td><span class="badge">Մշակման մեջ</span></td>
            </tr>
            <tr>
                <td>0231</td>
                <td>Moodle մուտք</td>
                <td>12.08.2026</td>
                <td>ՏՏ բաժին</td>
                <td><span class="badge">Հաստատված</span></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section id="portfolio" class="module-section"><h2><?= I18n::translate('academic_portfolio') ?></h2>
        <form data-demo-form>
            <div class="form-grid">
                <label class="field">
                    <?= I18n::translate('title_of_the_work') ?>
                    <input required></label>
                <label class="field"><?= I18n::translate('type') ?>
                    <select>
                        <option>Հոդված</option>
                        <option>Զեկույց</option>
                        <option>Հետազոտություն</option>
                    </select>
                </label>
                <label class="field"><?= I18n::translate('file_or_url') ?>
                    <input>
                </label>
            </div>
            <button class="btn" type="submit"><?= I18n::translate('add') ?></button>
            <div class="notice hidden" data-confirmation>Գիտական աշխատանքն ավելացվել է պորտֆոլիոյում։</div>
        </form>
    </section>
</main>