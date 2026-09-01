<?php


/** @var frontend\models\AdmissionForm $model */

use common\components\I18n;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$content = $this->context->pageData['content'];
?>
    <section class="inner-section">
        <div class="wrap inner-grid-2">
            <div>
                <?php if (Yii::$app->session->hasFlash('admission_successfully_sent')): ?>
                    <div class="eyebrow red"><?= Yii::$app->session->getFlash('admission_successfully_sent') ?></div>
                <?php endif; ?>

                <div class="eyebrow red"><?= I18n::translate('applicant_form') ?></div>
                <h2 class="display-inner"><?= I18n::translate('start_application') ?></h2>
                <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                                'template' => "{input}\n{hint}\n{error}",
                                'options' => [
                                        'class' => 'field-wrapper',
                                ],
                        ]
                ]); ?>

                <div class="field">
                    <label for="name"><?= I18n::translate('name') ?></label>
                    <?= $form->field($model, 'name')->textInput(['id' => 'name'])->label(false) ?>
                </div>
                <div class="field">
                    <label for="surname"><?= I18n::translate('surname') ?></label>
                    <?= $form->field($model, 'surname')->textInput(['id' => 'surname'])->label(false) ?>
                </div>

                <div class="field">
                    <label for="email"><?= I18n::translate('email') ?></label>
                    <?= $form->field($model, 'email')->textInput(['id' => 'email'])->label(false) ?>
                </div>

                <div class="field">
                    <label for="phone"><?= I18n::translate('phone') ?></label>
                    <?= $form->field($model, 'phone')->textInput(['id' => 'phone'])->label(false) ?>
                </div>

                <div class="field">
                    <label for="education_level"><?= I18n::translate('education_level') ?></label>
                    <?= $form->field($model, 'education_level')->dropDownList($education_levels, ['id' => 'education_level']) ?>
                </div>

                <div class="field"><label for="educational_programs"><?= I18n::translate('program') ?></label>
                    <?= $form->field($model, 'educational_programs')->dropDownList([], ['id' => 'educational_programs']) ?>
                </div>

                <div class="field full">
                    <?= $form->field($model, 'consent_processing_personal_data')->checkbox(['id' => 'consent_processing_personal_data'])->label(false) ?>
                    <label>

                        <?= I18n::translate('iconsent_processing_personal_data') ?>

                    </label>
                </div>
                <div class="field full">
                    <?= Html::submitButton(I18n::translate('continue_application') . '→', ['class' => 'btn-red']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
            <aside>
                <div class="meta-label">Ինչ է պետք</div>
                <div class="facts-list">
                    <div><strong>01</strong><span>Անձը հաստատող փաստաթուղթ</span></div>
                    <div><strong>02</strong><span>Կրթության մասին փաստաթուղթ</span></div>
                    <div><strong>03</strong><span>Ընտրված ծրագրին համապատասխան հավելյալ նյութեր</span></div>
                </div>
            </aside>
        </div>
    </section>
    <section class="rich-photo-section">
        <div class="wrap">
            <div class="photo-feature-split">
                <div class="visual"><img src="/images/editorial/reading-students.jpg" alt="ՀՊՏՀ ուսանողներ"></div>
                <div class="photo-feature-copy">
                    <div class="eyebrow red"><?= I18n::translate('educational_environment') ?></div>
                    <h2><?= $content->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?></h2>
                    <?= $content->getTranslation(Yii::$app->globalData->lang)->text ?? '' ?>

                    <a class="btn-red" href="/<?= Yii::$app->globalData->lang ?>/education/all-programs">
                        <?= I18n::translate('view_educational_programs') ?> →
                    </a>
                </div>
            </div>
        </div>
    </section>


<?php
$this->registerJsFile(
        '@web/js/admission.js',
        [
                'depends' => [\yii\web\JqueryAsset::class],
                'position' => \yii\web\View::POS_END,
        ]
);
?>