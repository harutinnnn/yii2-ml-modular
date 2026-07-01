<?php

/** @var yii\web\View $this */

/** @var backend\modules\journal\models\JournalForm $model */

use common\models\Journal;
use common\widgets\ckeditor\CkEditor;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


$languages = $model->getLanguages();
?>

<div class="journal-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <?= $form->field($model, "doi_prefix")->textInput(['maxlength' => true]) ?>

            <?php if (isset($model->journal->id) ): ?>
                <?= $form->field($model, "doi_suffix")->textInput(['maxlength' => true, 'disabled' => true]) ?>
            <?php endif; ?>

            <?=
            $form->field($model, 'authors')->widget(Select2::classname(), [
                    'data' => $authors ?? [],
                    'value' => $model->authors ?? [],
                    'options' => [
                            'placeholder' => 'Select categories...',
                            'multiple' => true, // This enables multi-select
                    ],
                    'pluginOptions' => [
                            'allowClear' => true, // Adds a clear button
                    ],
            ]);
            ?>

            <?= $form->field($model, 'status')->dropDownList(Journal::statusOptions()) ?>
            <?= $form->field($model, 'year')->dropDownList(array_combine(range(date('Y'), 2000), range(date('Y'), 2000))) ?>

            <?= $form->field($model, "number")->textInput(['maxlength' => true]) ?>

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
                    <div
                            class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                            id="lang-pane-<?= Html::encode($language->code) ?>"
                            role="tabpanel"
                            aria-labelledby="lang-tab-<?= Html::encode($language->code) ?>"
                    >
                        <?= $form->field($model, "translations[{$language->code}][title]")
                                ->label("Title ({$language->name})")
                                ->textInput([
                                        'maxlength' => true,
                                        'placeholder' => "Title in {$language->name}",
                                ]) ?>

                        <?= $form->field($model, "translations[{$language->code}][description]")
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
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->journal === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
