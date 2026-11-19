<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\EventsForm $model */

$this->title = 'Update event applicant #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php

/** @var yii\web\View $this */

/** @var backend\modules\news\models\EventsForm $model */

use common\components\StatusList;
use common\models\Events;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

?>

<div class="post-form">
    <?php $form = ActiveForm::begin(['id' => 'events-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-body">


            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(StatusList::statusOptionsShort()) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, "first_name")->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "last_name")->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "email")->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "phone")->textInput() ?>
                </div>


            </div>

        </div>
    </div>

    <div class="card card-outline card-primary">

        <div class="card-footer">
            <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
