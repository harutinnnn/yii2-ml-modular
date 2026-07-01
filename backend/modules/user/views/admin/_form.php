<?php

use backend\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\modules\user\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(
                            User::statusOptions()
                    ) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">

                    <?= $form->field($model, "email") ?>

                </div>

                <div class="col-md-6">

                    <?= $form->field($model, "full_name") ?>

                </div>
            </div>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
