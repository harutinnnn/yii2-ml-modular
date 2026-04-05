<?php

/** @var yii\web\View $this */
/** @var common\models\Setting $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
?>

<div class="card card-primary">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput([
            'maxlength' => true,
        ]) ?>

        <?= $form->field($model, 'key')->textInput([
            'maxlength' => true,
        ]) ?>

        <?= $form->field($model, 'value')->textarea([
            'rows' => 6,
        ]) ?>

        <div class="form-group mb-0">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
