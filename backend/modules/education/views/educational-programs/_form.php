<?php

/** @var yii\web\View $this */

/** @var backend\modules\education\models\EducationalProgramsForm $model */


use common\models\EducationalPrograms;
use common\widgets\ckeditor\CkEditor;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$languages = $model->getLanguages();
$editorConfigs = [];
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

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

                        <?= $form->field($model, "translations[{$language->code}][desc]")
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


                        <div class="row">
                            <div class="col-md-6">

                                <?= $form->field($model, "translations[{$language->code}][doc_about_program]")->fileInput() ?>
                            </div>
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
