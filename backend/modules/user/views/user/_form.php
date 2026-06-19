<?php

use backend\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/** @var yii\widgets\ActiveForm $form */
?>

    <div class="user-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="card card-primary">
            <div class="card-body">
                <?= $form->field($model, 'status')->dropDownList(
                        $model->id ?
                                User::statusOptions() :
                                [User::STATUS_INACTIVE => 'Pending']
                ) ?>


                <?= $form->field($model, "full_name")
                        ->label("Full name")
                        ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Full name",
                        ]) ?>

                <?= $form->field($model, "username")
                        ->label("Username")
                        ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Username",
                        ]) ?>

                <?= $form->field($model, "email")
                        ->label("Email")
                        ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Email",
                        ]) ?>



                <?= $form->field($model, "password")
                        ->label("Password")
                        ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Password",
                                'id' => 'user-password'
                        ]) ?>
                <?=
                Html::button('Generate Password', ['onclick' => 'generatePassword(8)','class' =>'btn btn-primary'])

                ?>


            </div>
        </div>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php $this->registerJsFile('/admin/js/scripts.js'); ?>