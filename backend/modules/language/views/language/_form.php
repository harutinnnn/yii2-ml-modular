<?php

/** @var yii\web\View $this */
/** @var common\models\Language $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
?>

<div class="card card-primary">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'code')->textInput([
            'maxlength' => true,
            'placeholder' => 'en',
        ]) ?>

        <?= $form->field($model, 'name')->textInput([
            'maxlength' => true,
            'placeholder' => 'English',
        ]) ?>

        <?= $form->field($model, 'sort_order')->input('number', ['min' => 0]) ?>

        <?= $form->field($model, 'is_default')->checkbox() ?>

        <?= $form->field($model, 'is_active')->checkbox() ?>

        <div class="form-group mb-0">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
