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
                    <?= $form->field($model, "teacher_academic_degree")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "Teacher degree",
                            ]) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, "teacher_position")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "Position",
                            ]) ?>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'faculty')->dropDownList(
                            \common\models\Faculties::getFalcultiesKeyVal(),
                            ['id' => 'faculty']
                    ) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'chair')->dropDownList(
                            [],
                            ['id' => 'chair']
                    ) ?>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, "first_name")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "First name",
                            ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "last_name")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "Last name",
                            ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, "middle_name")
                            ->textInput([
                                    'maxlength' => true,
                                    'placeholder' => "Middle name",
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

    let chair = {$model->chair};
    
    let faculty = $('#faculty');
    
    $('#faculty').change(function (){
        getChairsByFaculty($(this).val())    
    })
    
    function getChairsByFaculty(faculty) {
        $.ajax({
            type: 'GET',
            url: '/admin/user/teacher/get-chairs',
            data: {faculty_id: faculty},
            dataType: 'json',
            beforeSend: function (data) {
                $('#faculty').attr('disabled',true)
                $('#chair').html('')
            },
            success: function (data) {
                if(data){
                    $('#chair').html('')
                    
                    $.each(data,function (i,v){
                    
                        let selected = (i.toString() === chair.toString()) ? 'selected' : '' 
                        $('#chair').append('<option value="'+i+'" '+selected+'>'+ v+'</option>');
                        
                    })
                }
                $('#faculty').attr('disabled',false)
            },
            error: function (data) {
                $('#faculty').attr('disabled',false)
            }
        })    
    }
    getChairsByFaculty(faculty.val())
    
    
JS, \yii\web\View::POS_END);
?>
