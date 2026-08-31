<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationalProgramsForm $model */

$this->title = 'Update Educational Program #' . $model->education_programs?->id;
$this->params['breadcrumbs'][] = ['label' => 'Educational Program', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'levels' => $levels ?? []]) ?>
