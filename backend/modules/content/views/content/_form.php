<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\ContentForm $model */

use common\models\Content;
use common\widgets\ckeditor\CkEditor;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$languages = $model->getLanguages();
?>

<div class="content-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->dropDownList(Content::statusOptions()) ?>
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
                            ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Title in {$language->name}",
                            ]) ?>

                        <?= $form->field($model, "translations[{$language->code}][text]")
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
            <?= Html::submitButton($model->content === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
