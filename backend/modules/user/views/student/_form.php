<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Student $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="card card-primary">
        <div class="card-body">

            <div class="row">
                <?= $form->errorSummary($model) ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList(
                            \common\models\Student::statusOptions()
                    ) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'education_level')->dropDownList(
                            \common\models\EducationLevels::getFalcultiesKeyVal(),
                            ['id' => 'education_level']
                    ) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'educational_programs')->dropDownList(
                            [],
                            ['id' => 'educational_programs']
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
                    <?= $form->field($model, "university_email")
                            ->textInput([
                                    'disabled' => intval($model->id) ? 'disabled' : false,
                                    'maxlength' => true,
                                    'placeholder' => "Email",
                            ]) ?>
                </div>

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

    let educational_programs = {$model->educational_programs};
    
    let faculty = $('#education_level');
    
    $('#education_level').change(function (){
        getChairsByFaculty($(this).val())    
    })
    
    function getChairsByFaculty(education_level) {
        $.ajax({
            type: 'GET',
            url: '/admin/user/applicant/get-chairs',
            data: {education_level: education_level},
            dataType: 'json',
            beforeSend: function (data) {
                $('#education_level').attr('disabled',true)
                $('#educational_programs').html('')
            },
            success: function (data) {
                if(data){
                    $('#educational_programs').html('')
                    
                    $.each(data,function (i,v){
                    
                        let selected = (i.toString() === educational_programs.toString()) ? 'selected' : '' 
                        $('#educational_programs').append('<option value="'+i+'" '+selected+'>'+ v+'</option>');
                        
                    })
                }
                $('#education_level').attr('disabled',false)
            },
            error: function (data) {
                $('#education_level').attr('disabled',false)
            }
        })    
    }
    getChairsByFaculty(faculty.val())
    
    
JS, \yii\web\View::POS_END);
?>
