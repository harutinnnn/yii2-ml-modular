<?php


use common\models\Journal;
use common\widgets\ckeditor\CkEditor;
use kartik\select2\Select2;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;


/** @var yii\web\View $this */
/** @var common\models\Journal $model */

$this->title = 'Create Journal';
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="journal-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <?= $form->field($model, 'journal_id')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'adminIds')->widget(Select2::classname(), [
                    'data' => $adminUsers ?? [],
                    'value' => $model->adminIds ?? [],
                    'options' => [
                            'placeholder' => 'Select categories...',
                            'multiple' => true, // This enables multi-select
                    ],
                    'pluginOptions' => [
                            'allowClear' => true, // Adds a clear button
                    ],
            ]); ?>

        </div>
    </div>


    <div class="card-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>