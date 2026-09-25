<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationPlanForm $model */

$this->title = 'Update Educational Plan #' . $model->education_plan?->id;
$this->params['breadcrumbs'][] = ['label' => 'Educational Plan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-plans', ['model' => $model]) ?>
