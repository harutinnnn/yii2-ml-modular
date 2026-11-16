<?php

use common\components\I18n;
use common\models\ArticlesAndBooksRequests;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \frontend\models\TeacherDataForm $teacherDataForm */
/** @var \common\models\ArticlesAndBooksRequests $articlesAndBooksRequests */
/** @var \common\models\User $user */


$facultyTitle = $faculty?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";
$chairTitle = $chair?->getTranslation(Yii::$app->globalData->lang)->title ?? "-";
?>
<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow"><?= I18n::translate('lecturers_personal_dashboard') ?></div>
            <h1><?= I18n::translate('scientific_and_administrative_work') ?></h1>
            <p><?= I18n::translate('lecturers_personal_dashboard_sub_text') ?></p>
        </div>
    </section>

    <section id="profile" class="module-section">
        <h2 id="persona-data"><?= I18n::translate('personal_data') ?></h2>

        <?php if (Yii::$app->session->hasFlash('message')): ?>
            <p role="status" data-status><?= Yii::$app->session->getFlash('message') ?></p>
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
                <?= $form->field($teacherDataForm, 'first_name')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('surname') ?>
                <?= $form->field($teacherDataForm, 'last_name')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>

            <label class="field"><?= I18n::translate('middle_name') ?>
                <?= $form->field($teacherDataForm, 'middle_name')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>

            <label class="field">
                <?= I18n::translate('phone') ?>
                <?= $form->field($teacherDataForm, 'phone')->textInput([])->label(false) ?>
            </label>
            <label class="field"><?= I18n::translate('email') ?>
                <?= $form->field($teacherDataForm, 'email')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>

            <label class="field"><?= I18n::translate('contact_email') ?>
                <?= $form->field($teacherDataForm, 'contact_email')->textInput([])->label(false) ?>
            </label>


            <label class="field"><?= I18n::translate('teacher_academic_degree') ?>
                <?= $form->field($teacherDataForm, 'teacher_academic_degree')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>

            <label class="field"><?= I18n::translate('teacher_position') ?>
                <?= $form->field($teacherDataForm, 'teacher_position')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>


            <label class="field">
                <?= I18n::translate('daculty_department') ?>
                <input value="<?= $facultyTitle ?> / <?= $chairTitle ?>" disabled>
            </label>
            <label class="field">
                <?= I18n::translate('status') ?>
                <input value="<?= I18n::translate(\common\components\StatusList::getStatusLabel($user->status)) ?>"
                       disabled>
            </label>
        </div>


        <h3><?= I18n::translate('professional_information') ?></h3>
        <div class="form-grid">
            <label class="field">
                <?= I18n::translate('work_experience_and_years_of_service') ?>
                <?= $form->field($teacherDataForm, 'teacher_work_experience')->textarea()->label(false) ?>
            </label>
            <label class="field">
                <?= I18n::translate('language_proficiency') ?>
                <?= $form->field($teacherDataForm, 'teacher_language_proficiency')->textarea()->label(false) ?>
            </label>
            <label class="field">
                <?= I18n::translate('certificates') ?>
                <?= $form->field($teacherDataForm, 'teacher_certificates')->textarea()->label(false) ?>
            </label>
            <label class="field">
                <?= I18n::translate('subjects_taught') ?>
                <?= $form->field($teacherDataForm, 'teacher_subjects_taught')->textarea()->label(false) ?>
            </label>
        </div>

        <?= Html::submitButton(I18n::translate('save'), ['class' => 'btn', 'value' => 3]) ?>

        <p role="status" data-status></p>

        <?php ActiveForm::end(); ?>
    </section>

    <section class="module-section" id="research">

        <div><h2><?= I18n::translate('articles_and_books') ?></h2>
            <p><?= I18n::translate('articles_and_books_sub_text') ?></p>

            <?php if (Yii::$app->session->hasFlash('article_request_message')): ?>
                <p role="status" data-status><?= Yii::$app->session->getFlash('article_request_message') ?></p>
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
                <?php if (isset($articlesAndBooksRequests->id)): ?>
                    <div style="display: none">
                        <?= $form->field($articlesAndBooksRequests, 'id')->hiddenInput()->label(false) ?>
                    </div>
                <?php endif; ?>

                <label class="field">
                    <?= I18n::translate('title_of_the_work') ?> *
                    <?= $form->field($articlesAndBooksRequests, 'title')->textInput()->label(false) ?>

                </label>
                <label class="field">
                    <?= I18n::translate('type') ?> *
                    <?=
                    $form->field($articlesAndBooksRequests, 'type')
                            ->dropDownList(ArticlesAndBooksRequests::optsType())
                            ->label(false)
                    ?>
                </label>
                <label class="field">
                    <?= I18n::translate('authors') ?> *
                    <?= $form->field($articlesAndBooksRequests, 'authors')->textInput()->label(false) ?>
                </label>
                <label class="field">
                    <?= I18n::translate('publication_year') ?> *
                    <?= $form->field($articlesAndBooksRequests, 'publication_year')->textInput(['type' => 'number'])->label(false) ?>
                </label>
                <label class="field">
                    <?= I18n::translate('journal_publisher') ?> *
                    <?= $form->field($articlesAndBooksRequests, 'magazine_publisher')->textInput()->label(false) ?>
                </label>
                <label class="field">
                    <?= I18n::translate('publication_link') ?> *
                    <?= $form->field($articlesAndBooksRequests, 'publication_link')->textInput()->label(false) ?>
                </label>
                <label class="field">
                    <?= I18n::translate('doi_isbn') ?>
                    <?= $form->field($articlesAndBooksRequests, 'doi')->textInput()->label(false) ?>
                </label>
                <label class="field">
                    <?= I18n::translate('database') ?>
                    <?=
                    $form->field($articlesAndBooksRequests, 'base')
                            ->dropDownList(ArticlesAndBooksRequests::optsBase())
                            ->label(false)
                    ?>
                </label>
            </div>

            <?= Html::submitButton(I18n::translate('submit_for_approval'), ['class' => 'btn', 'value' => 3]) ?>

            <p role="status" data-status></p>
            <?php ActiveForm::end(); ?>
            <h3><?= I18n::translate('my_submitted_materials') ?></h3>
            <div data-my-publications>
                <?php if (isset($articlesAndBooksRequestsList) && !empty($articlesAndBooksRequestsList)): ?>
                    <?php foreach ($articlesAndBooksRequestsList as $articlesAndBooksRequestsItem): ?>

                        <article class="workflow-record" id="articles-and-books-requests-<?= $articlesAndBooksRequestsItem->id ?>">
                            <h3><?= $articlesAndBooksRequestsItem->title ?></h3>
                            <p class="status"><?= I18n::translate(\common\components\StatusList::getStatusLabel($articlesAndBooksRequestsItem->status)) ?></p>

                            <?php if ($articlesAndBooksRequestsItem->status == \common\components\StatusList::STATUS_PENDING): ?>
                                <a href="?request_id=<?= $articlesAndBooksRequestsItem->id ?>#research">
                                    <button class="btn secondary" type="button">Խմբագրել և կրկին ներկայացնել</button>
                                </a>

                            <?php endif; ?>

                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <section id="requests" class="module-section"><h2><?= I18n::translate('certificate_requests') ?></h2>
        <p><?= I18n::translate('certificate_requests_sub_text') ?></p>
        <button class="btn"><?= I18n::translate('new_request') ?></button>
        <table class="data-table">
            <thead>
            <tr>
                <th>N</th>
                <th>Տեսակ</th>
                <th>Ամսաթիվ</th>
                <th>Կարգավիճակ</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>T-118</td>
                <td>Աշխատանքի վայրի տեղեկանք</td>
                <td>18.08.2026</td>
                <td><span class="badge">Կատարված</span></td>
            </tr>
            </tbody>
        </table>
    </section>
    <section class="module-section"><a class="btn secondary" href="reports.html#research">Դիտել իմ գիտական
            հաշվետվությունը</a></section>
</main>