<?php

use common\widgets\ckeditor\CkEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\JournalAuthors $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="journal-authors-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">


            <div class="row">
                <div class="col-md-6">
                    <label for="author-media_file">Author image</label>

                    <?php if ($model->img): ?>
                        <div class="col-md-6" id="mainImageBox">
                            <?= Html::img($model->getUploadUrl('img'), ['class' => 'img-thumbnail']) ?>
                        </div>

                    <?php else: ?>
                        <div class="col-md-6" id="mainImageBox"></div>
                    <?php endif; ?>
                    <br>

                    <?= $form->field($model, 'img')->fileInput([  'onchange' => 'ReaderImageDisplay(event,"mainImageBox",250)'])->label(false) ?>
                </div>
            </div>


            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, "bio")
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


            <div class="card-footer">
                <?= Html::submitButton($model->id === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
            </div>

        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
