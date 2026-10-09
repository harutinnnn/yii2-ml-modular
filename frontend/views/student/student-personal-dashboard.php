<?php

use common\components\I18n;
use common\models\ApplicationsAndInquiries;
use common\models\ScientificPortfolio;
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


            <label class="field"><?= I18n::translate('email') ?>
                <?= $form->field($studentDataModel, 'email')->textInput(['readonly' => 'readonly'])->label(false) ?>
            </label>

            <label class="field"><?= I18n::translate('contact_email') ?>
                <?= $form->field($studentDataModel, 'contact_email')->textInput([])->label(false) ?>
            </label>

            <div style="clear: both"></div>

            <label class="field"><?= I18n::translate('faculty_specialty') ?>
                <input value="<?= $facultyTitle ?> / <?= $chairTitle ?>" disabled>
            </label>

            <label class="field"><?= I18n::translate('phone') ?>
                <?= $form->field($studentDataModel, 'phone')->textInput([])->label(false) ?>
            </label>

            <label class="field"><?= I18n::translate('passport_details') ?>
                <?= $form->field($studentDataModel, 'passport_details')->textInput([])->label(false) ?>
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

    <section class="module-section" id="requests">
        <div>
            <h2><?= I18n::translate('applications_and_inquiries') ?></h2>

            <?php $form = ActiveForm::begin([
                    'options' => [
                            'name' => 'inquiry-form',
                            'class' => 'form-grid',
                    ],
                    'fieldConfig' => [
                            'template' => "{input}\n{hint}\n{error}",
                            'options' => [

                            ],
                    ]
            ]); ?>

            <div style="display: none">
                <?= $form->field($applicationsAndInquiries, 'user_id')->hiddenInput([])->label(false) ?>
            </div>

            <label class="field">
                <?= I18n::translate('application_type') ?>

                <?=
                $form->field($applicationsAndInquiries, 'type')
                        ->dropDownList(ApplicationsAndInquiries::getTypes())
                        ->label(false)
                ?>

            </label>

            <label class="field">
                <?= I18n::translate('subject') ?>
                <?= $form->field($applicationsAndInquiries, 'subject')->textInput([])->label(false) ?>
            </label>

            <label class="field full">
                <?= I18n::translate('description') ?>
                <?= $form->field($applicationsAndInquiries, 'description')->textarea([])->label(false) ?>
            </label>

            <div class="field full">
                <?= Html::submitButton(I18n::translate('submit'), ['class' => 'btn', 'value' => 2]) ?>
                <?php if (Yii::$app->session->hasFlash('applications_and_inquiries_message')): ?>
                    <p role="status"
                       data-status><?= Yii::$app->session->getFlash('applications_and_inquiries_message') ?></p>
                <?php endif; ?>
            </div>


            <?php ActiveForm::end(); ?>
            <h3><?= I18n::translate('my_applications') ?></h3>
            <div data-request-list>

                <?php if (isset($applicationsAndInquiriesList) && !empty($applicationsAndInquiriesList)): ?>
                    <?php foreach ($applicationsAndInquiriesList as $item): ?>
                        <article class="workflow-record">
                            <h3><?= $item->subject ?></h3>
                            <p class="status"><?= I18n::translate($item->type) ?>
                                · <?= date('d/m/Y', $item->created_at) ?></p>
                            <p> <?= $item->description ?></p>

                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="portfolio" class="module-section"><h2><?= I18n::translate('scientific_portfolio') ?></h2>
        <?php $form = ActiveForm::begin([
                'action' => '#portfolio',
                'options' => [
                        'name' => 'scientific-portfolio-form',
                        'enctype' => 'multipart/form-data'
                ],
                'fieldConfig' => [
                        'template' => "{input}\n{hint}\n{error}"
                ]
        ]); ?>
        <div class="form-grid">
            <div style="display: none">
                <?= $form->field($scientificPortfolio, 'user_id')->hiddenInput([])->label(false) ?>
            </div>
            <label class="field">
                <?= I18n::translate('title_of_the_work') ?>
                <?= $form->field($scientificPortfolio, 'job_title')->textInput([])->label(false) ?>
            </label>
            <label class="field">
                <?= I18n::translate('type') ?>
                <?=
                $form->field($scientificPortfolio, 'type')
                        ->dropDownList(ScientificPortfolio::optsType())
                        ->label(false)
                ?>
            </label>


            <div style="clear: both"></div>


            <label class="field">

                <?= I18n::translate('protfolio_content_type') ?>

                <?=
                $form->field($scientificPortfolio, 'content_type')
                        ->dropDownList(ScientificPortfolio::optsContentType(), ['id' => 'content_type'])
                        ->label(false)
                ?>
            </label>



            <label class="field" id="sc-po-url">
                <?= I18n::translate('file_or_url') ?>
                <?= $form->field($scientificPortfolio, 'file_or_url')->textInput([])->label(false) ?>
            </label>

            <label class="field d-none" id="sc-po-file">
                <?= I18n::translate('file') ?>
                <?= $form->field($scientificPortfolio, 'file')->fileInput()->label(false) ?>
            </label>

        </div>
        <?= Html::submitButton(I18n::translate('add'), ['class' => 'btn', 'value' => 3]) ?>

        <?php if (Yii::$app->session->hasFlash('portfolio_message')): ?>
            <div class="notice" data-confirmation><?= Yii::$app->session->getFlash('portfolio_message') ?></div>
        <?php endif; ?>
        <?php ActiveForm::end(); ?>
    </section>
</main>


<?php

$fileType = ScientificPortfolio::CONTENT_TYPE_FILE;
$urlType = ScientificPortfolio::CONTENT_TYPE_URL;

$this->registerJs(<<<JS

    const TYPE_FILE = "{$fileType}"
    const TYPE_URL = "{$urlType}"

    $('#content_type').change(function() {
        
        if($(this).val() === TYPE_URL) {
            $('#sc-po-url').removeClass('d-none')
            $('#sc-po-file').addClass('d-none')
        }else{
            $('#sc-po-url').addClass('d-none')
            $('#sc-po-file').removeClass('d-none')
        }
        
    })
    
    
JS, \yii\web\View::POS_END);
?>
