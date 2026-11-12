<?php

/** @var yii\web\View $this */

/** @var backend\modules\library\models\LibraryItemForm $model */


use common\models\LibraryItem;
use backend\modules\library\widgets\NestedActiveField;
use common\models\TranslationModel;
use common\widgets\ckeditor\CkEditor;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$languages = $model->getLanguages();
$editorConfigs = [];
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin([
            'fieldClass' => NestedActiveField::class,
            'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <div class="card card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'library_module_id')->dropDownList($libraryModules ?? []) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(LibraryItem::statusOptions()) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'type')->dropDownList(LibraryItem::optsType(), ['id' => 'type']) ?>
                </div>

                <div class="col-md-12" id="url-box" style="display: <?=  $model->type == LibraryItem::TYPE_FILE ? 'none' : '' ?>">
                    <?= $form->field($model, 'url')->textInput() ?>
                </div>
                <div class="col-md-12" id="file-box" style="display: <?=  $model->type == LibraryItem::TYPE_URL ? 'none' : '' ?>">
                    <?= $form->field($model, 'libraryItemFile')->fileInput() ?>
                    <?php if (strlen(trim($model->file))): ?>
                        <p class="mb-0">
                            <a href="<?= $model->file ?>" target="_blank">aa
                                <?= TranslationModel::getTranslation($model, 'en') ?>
                            </a>
                        </p>
                    <?php endif; ?>
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
                        <?= $form->field($model, "translations[{$language->code}][text]")->label("text ({$language->name})")->widget(CkEditor::class, [
                                'elfinderController' => ['elfinder', 'filter' => 'image', 'lang' => 'en'],
                                'clientOptions' => [
                                        'height' => 220,
                                        'toolbar' => [
                                                ['name' => 'document', 'items' => ['Source']],
                                                ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
                                                ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Underline', 'RemoveFormat']],
                                                ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', 'Blockquote']],
                                                ['name' => 'links', 'items' => ['Link', 'Unlink']],
                                                ['name' => 'insert', 'items' => ['Image', 'Table', 'HorizontalRule', 'SpecialChar']],
                                                ['name' => 'styles', 'items' => ['Format']],
                                        ],
                                ],
                        ]) ?>


                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->library_item === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>


<?php
$this->registerJs(<<<JS
    
    $('#type').change(function() {
        if($(this).val() === 'file'){
            $('#url-box').hide();
            $('#file-box').show();
        }else{
            $('#url-box').show();
            $('#file-box').hide();
        }
    })
    
    if($('#type').val() === 'file'){
        $('#url-box').hide();
        $('#file-box').show();
    }else{
        $('#url-box').show();
        $('#file-box').hide();
    }
    
    JS, \yii\web\View::POS_END);
?>
