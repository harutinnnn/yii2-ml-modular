<?php

/** @var yii\web\View $this */
/** @var backend\modules\frontendlanguage\models\FrontendLanguageForm $model */

use common\models\FrontendLanguage;
use common\widgets\ckeditor\CkEditorAsset;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;

$languages = $model->getLanguages();
CkEditorAsset::register($this);
?>

<div class="frontend-language-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(FrontendLanguage::statusOptions()) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'type')->dropDownList(FrontendLanguage::typeOptions(), ['id' => 'frontend-language-type']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($languages as $index => $language): ?>
                    <li class="nav-item">
                        <a
                            class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                            id="frontend-language-tab-<?= Html::encode($language->code) ?>"
                            data-toggle="pill"
                            href="#frontend-language-pane-<?= Html::encode($language->code) ?>"
                            role="tab"
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
                    <?php $fieldId = Html::getInputId($model, "translations[{$language->code}][text]"); ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="frontend-language-pane-<?= Html::encode($language->code) ?>" role="tabpanel">
                        <?= $form->field($model, "translations[{$language->code}][text]")
                            ->label("Text ({$language->name})")
                            ->textarea([
                                'rows' => 8,
                                'class' => 'form-control js-frontend-language-text',
                                'data-editor-id' => $fieldId,
                            ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->frontendLanguage === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$browseUrl = \mihaildev\elfinder\ElFinder::getManagerUrl('elfinder', ['filter' => 'image', 'lang' => 'en']);
$ckeditorOptions = Json::htmlEncode([
    'height' => 300,
    'filebrowserBrowseUrl' => $browseUrl,
    'filebrowserImageBrowseUrl' => $browseUrl,
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
]);
$initialType = Json::htmlEncode($model->type);
$this->registerJs(<<<JS
(function () {
    const typeField = document.getElementById('frontend-language-type');
    const editors = document.querySelectorAll('.js-frontend-language-text');
    const editorOptions = {$ckeditorOptions};

    function enableEditor(textarea) {
        const id = textarea.id;
        if (!id || typeof CKEDITOR === 'undefined' || CKEDITOR.instances[id]) {
            return;
        }
        CKEDITOR.replace(id, editorOptions);
    }

    function disableEditor(textarea) {
        const id = textarea.id;
        if (!id || typeof CKEDITOR === 'undefined' || !CKEDITOR.instances[id]) {
            return;
        }
        CKEDITOR.instances[id].destroy(true);
    }

    function syncEditorMode() {
        const useEditor = typeField.value === 'content';
        editors.forEach(function (textarea) {
            if (useEditor) {
                enableEditor(textarea);
            } else {
                disableEditor(textarea);
            }
        });
    }

    if (typeField) {
        typeField.value = {$initialType};
        typeField.addEventListener('change', syncEditorMode);
        syncEditorMode();
    }
})();
JS);
?>
