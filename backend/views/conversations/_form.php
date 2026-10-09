<?php

use common\models\Conversations;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Conversations $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="conversations-form">

    <?php $form = ActiveForm::begin([]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'type')->dropDownList(
                            Conversations::optsType()
                    ) ?>
                </div>
            </div>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
