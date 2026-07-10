<?php

/** @var yii\web\View $this */

/** @var backend\modules\journal\models\JournalForm $model */

use common\models\Journal;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<div class="journal-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <?= $form->field($model, 'journal_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'status')->dropDownList(Journal::statusOptions()) ?>
            <?= $form->field($model, 'year')->dropDownList(array_combine(range(date('Y'), 2000), range(date('Y'), 2000))) ?>
            <?= $form->field($model, "number")->textInput(['maxlength' => true]) ?>

        </div>
    </div>

    <div class="card-footer">
        <?= Html::submitButton($model->journal === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
