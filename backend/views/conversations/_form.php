<?php

use common\models\Conversations;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Conversations $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="conversations-form">


    <div class="card card-primary">

        <?php if ($model->id): ?>
            <div class="card-comments p-2">
                <a href="/admin/conversations/members?conversation_id=<?= $model->id ?>">
                    <button type="submit" class="btn btn-success">Members <i class="fas fa-users"></i></button>
                </a>
            </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin([]); ?>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(
                            Conversations::statusOptions()
                    ) ?>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'type')->dropDownList(
                            Conversations::optsType()
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'moderator')->dropDownList(
                            $moderators ?? []
                    ) ?>
                </div>
            </div>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
