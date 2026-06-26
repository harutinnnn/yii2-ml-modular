<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Applicant $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(
                            \common\models\Applicant::statusOptions()
                    ) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'faculty_id')->dropDownList(
                            \common\models\Faculties::getFalcultiesKeyVal(),
                            ['id' => 'faculty_id']
                    ) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'chair_id')->dropDownList(
                            [],
                            ['id' => 'chair_id']
                    ) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, "first_name")
                            ->label("First name")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "First name",
                            ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "last_name")
                            ->label("First name")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "First name",
                            ]) ?>
                </div>
            </div>

            <div class="row">


                <div class="col-md-6">
                    <?= $form->field($model, "email")
                            ->textInput([
                                    'disabled' => intval($model->id) ? 'disabled' : false,
                                    'maxlength' => true,
                                    'placeholder' => "Email",
                            ]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, "phone")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "Phone",
                            ]) ?>
                </div>
            </div>

        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php $this->registerJsFile('/admin/js/scripts.js'); ?>

<?php

$this->registerJs(<<<JS

    let chair_id = {$model->chair_id};
    
    let faculty = $('#faculty_id');
    
    $('#faculty_id').change(function (){
        getChairsByFaculty($(this).val())    
    })
    
    function getChairsByFaculty(faculty_id) {
        $.ajax({
            type: 'GET',
            url: '/admin/user/applicant/get-chairs',
            data: {faculty_id: faculty_id},
            dataType: 'json',
            beforeSend: function (data) {
                $('#faculty_id').attr('disabled',true)
                $('#chair_id').html('')
            },
            success: function (data) {
                if(data){
                    $('#chair_id').html('')
                    
                    $.each(data,function (i,v){
                    
                        let selected = (i.toString() === chair_id.toString()) ? 'selected' : '' 
                        $('#chair_id').append('<option value="'+i+'" '+selected+'>'+ v+'</option>');
                        
                    })
                }
                $('#faculty_id').attr('disabled',false)
            },
            error: function (data) {
                $('#faculty_id').attr('disabled',false)
            }
        })    
    }
    getChairsByFaculty(faculty.val())
    
    
JS, \yii\web\View::POS_END);
?>
