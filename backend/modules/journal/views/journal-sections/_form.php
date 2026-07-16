<?php

use common\widgets\ckeditor\CkEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalSectionsForm $model */
/** @var yii\widgets\ActiveForm $form */

$languages = $model->getLanguages();

?>

<div class="journal-sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(\common\models\JournalSections::statusOptions()) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'journal_id')->dropDownList($journals ?? []) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'journal_section_type')->dropDownList(
                            \common\models\JournalSections::getTypesLabels()
                    ) ?>
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
                                id="chair-lang-tab-<?= Html::encode($language->code) ?>"
                                data-toggle="pill"
                                href="#chair-lang-pane-<?= Html::encode($language->code) ?>"
                                role="tab"
                                aria-controls="chair-lang-pane-<?= Html::encode($language->code) ?>"
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
                    <div
                            class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                            id="chair-lang-pane-<?= Html::encode($language->code) ?>"
                            role="tabpanel"
                            aria-labelledby="chair-lang-tab-<?= Html::encode($language->code) ?>"
                    >
                        <?= $form->field($model, "translations[{$language->code}][title]")
                                ->label("Title ({$language->name})")
                                ->textInput([
                                        'maxlength' => true,
                                        'placeholder' => "Title in {$language->name}",
                                ]) ?>

                        <?= $form->field($model, "translations[{$language->code}][content]")
                                ->label("Content ({$language->name})")
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
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->journalSections === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
