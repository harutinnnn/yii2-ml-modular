<?php

/** @var yii\web\View $this */

/** @var backend\modules\education\models\EducationalProgramsForm $model */


use common\models\EducationalPrograms;
use common\widgets\ckeditor\CkEditor;
use backend\modules\education\widgets\NestedActiveField;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$languages = $model->getLanguages();
$editorConfigs = [];
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
$documentFields = [
        'docAboutProgramFile' => ['doc_about_program', 'Document about program'],
        'docEducationalProgramGuideFile' => ['doc_educational_program_guide', 'Educational program guide'],
        'docSubjectListFile' => ['doc_subject_list', 'Subject list'],
        'docSubjectListRemoteFile' => ['doc_subject_list_remote', 'Remote subject list'],
];
?>

<div class="post-form">
    <?php $form = ActiveForm::begin([
            'fieldClass' => NestedActiveField::class,
            'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->dropDownList(EducationalPrograms::statusOptions()) ?>
            <?= $form->field($model, 'is_remote')->dropDownList([
                    1 => 'Yes',
                    0 => 'No',
            ]) ?>
            <?= $form->field($model, 'duration_by_year')->textInput(['type' => 'number', 'step' => .1]) ?>

            <?= $form->field($model, 'education_level')->dropDownList($levels ?? []) ?>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($languages as $index => $language): ?>
                    <li class="nav-item">
                        <a
                                class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                                id="lang-tab-<?= Html::encode($language->code) ?>"
                                data-toggle="pill"
                                href="#lang-pane-<?= Html::encode($language->code) ?>"
                                role="tab"
                                aria-controls="lang-pane-<?= Html::encode($language->code) ?>"
                                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                        >
                            <?= Html::encode(strtoupper($language->code)) ?>: <?= Html::encode($language->name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $index => $language): ?>
                    <?php
                    $titleAttribute = "translations[{$language->code}][title]";
                    $descriptionAttribute = "translations[{$language->code}][desc]";
                    ?>
                    <div
                            class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                            id="lang-pane-<?= Html::encode($language->code) ?>"
                            role="tabpanel"
                            aria-labelledby="lang-tab-<?= Html::encode($language->code) ?>"
                    >
                        <?= $form->field($model, $titleAttribute)
                                ->label("Title ({$language->name})")
                                ->textInput([
                                        'maxlength' => true,
                                        'placeholder' => "Title in {$language->name}",
                                ]) ?>

                        <?= $form->field($model, $descriptionAttribute)
                                ->label("Description ({$language->name})")
                                ->widget(CkEditor::class, [
                                        'elfinderController' => ['elfinder', 'filter' => 'image', 'lang' => 'en'],
                                        'clientOptions' => [
                                                'height' => 300,
                                                'toolbar' => [
                                                        ['name' => 'document', 'items' => ['Source']],
                                                        ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
                                                        ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Underline', 'RemoveFormat']],
                                                        ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', 'Blockquote']],
                                                        ['name' => 'links', 'items' => ['Link', 'Unlink']],
                                                        ['name' => 'insert', 'items' => ['Image', 'Table', 'HorizontalRule', 'SpecialChar']],
                                                        ['name' => 'styles', 'items' => ['Format']],
                                                        ['name' => 'colors', 'items' => ['TextColor', 'BGColor']],
                                                ],
                                        ],
                                ]) ?>

                        <?= $form->field($model, "translations[{$language->code}][imgFile]")
                                ->label("Image ({$language->name})")
                                ->fileInput([
                                        'accept' => '.png,.jpg,.jpeg,.gif,.webp,image/png,image/jpeg,image/gif,image/webp',
                                ]) ?>

                        <?php $currentImage = $model->getImage($language->code); ?>
                        <?php if ($currentImage): ?>
                            <div class="mb-3">
                                <?= Html::img($currentImage, [
                                        'alt' => "Current image ({$language->name})",
                                        'class' => 'img-thumbnail',
                                        'style' => 'max-width: 240px; max-height: 180px;',
                                ]) ?>
                            </div>
                        <?php endif; ?>

                        <div class="row">


                            <?php foreach ($documentFields as $uploadAttribute => [$documentAttribute, $documentLabel]): ?>
                                <div class="col-md-6">
                                    <?php
                                    $fileInputAttribute = "translations[{$language->code}][{$uploadAttribute}]";
                                    $removeInputAttribute = "translations[{$language->code}][{$uploadAttribute}Remove]";
                                    $fileInputId = Html::getInputId($model, $fileInputAttribute);
                                    $removeInputId = Html::getInputId($model, $removeInputAttribute);
                                    $currentDocumentId = $fileInputId . '-current';
                                    $removedNoticeId = $fileInputId . '-removed';
                                    $isMarkedForRemoval = !empty($model->translations[$language->code][$uploadAttribute . 'Remove']);
                                    ?>
                            <?= $form->field($model, $fileInputAttribute)
                                    ->label("{$documentLabel} ({$language->name})")
                                    ->fileInput([
                                                    'accept' => '.pdf,application/pdf',
                                                    'class' => 'form-control-file js-program-document-file',
                                                    'data-remove-target' => $removeInputId,
                                            'data-current-target' => $currentDocumentId,
                                            'data-removed-notice-target' => $removedNoticeId,
                                    ]) ?>
                                    <?= Html::activeHiddenInput($model, $removeInputAttribute, [
                                            'id' => $removeInputId,
                                            'value' => $isMarkedForRemoval ? 1 : 0,
                                    ]) ?>

                                    <?php $currentDocument = $model->getDocument($language->code, $documentAttribute); ?>
                                    <?php if ($currentDocument): ?>
                                        <div
                                                id="<?= Html::encode($currentDocumentId) ?>"
                                                class="mb-3 align-items-center"
                                                style="display: <?= $isMarkedForRemoval ? 'none' : 'flex' ?>; gap: .5rem;"
                                        >
                                            <?= Html::a(
                                                    'View current PDF',
                                                    $currentDocument,
                                                    ['target' => '_blank', 'rel' => 'noopener noreferrer']
                                            ) ?>
                                            <?= Html::button('<i class="fas fa-trash" aria-hidden="true"></i>', [
                                                    'type' => 'button',
                                                    'class' => 'btn btn-sm btn-outline-danger js-remove-program-document',
                                                    'title' => 'Remove uploaded PDF',
                                                    'aria-label' => 'Remove uploaded PDF',
                                                    'data-remove-target' => $removeInputId,
                                                    'data-file-target' => $fileInputId,
                                                    'data-current-target' => $currentDocumentId,
                                                    'data-removed-notice-target' => $removedNoticeId,
                                            ]) ?>
                                        </div>
                                        <p
                                                id="<?= Html::encode($removedNoticeId) ?>"
                                                class="mb-3 text-danger"
                                                style="display: <?= $isMarkedForRemoval ? 'block' : 'none' ?>;"
                                        >
                                            PDF will be removed when you save.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->education_programs === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJs(<<<'JS'
document.addEventListener('click', function (event) {
    const button = event.target.closest('.js-remove-program-document');
    if (!button) {
        return;
    }

    const removeInput = document.getElementById(button.dataset.removeTarget);
    const fileInput = document.getElementById(button.dataset.fileTarget);
    const currentDocument = document.getElementById(button.dataset.currentTarget);
    const removedNotice = document.getElementById(button.dataset.removedNoticeTarget);

    if (removeInput) removeInput.value = '1';
    if (fileInput) fileInput.value = '';
    if (currentDocument) currentDocument.style.display = 'none';
    if (removedNotice) removedNotice.style.display = 'block';
});

document.addEventListener('change', function (event) {
    const fileInput = event.target.closest('.js-program-document-file');
    if (!fileInput || !fileInput.files.length) {
        return;
    }

    const removeInput = document.getElementById(fileInput.dataset.removeTarget);
    const currentDocument = document.getElementById(fileInput.dataset.currentTarget);
    const removedNotice = document.getElementById(fileInput.dataset.removedNoticeTarget);

    if (removeInput) removeInput.value = '0';
    if (currentDocument) currentDocument.style.display = 'flex';
    if (removedNotice) removedNotice.style.display = 'none';
});
JS
);
?>
