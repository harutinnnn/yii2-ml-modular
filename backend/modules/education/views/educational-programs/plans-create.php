<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationPlanForm $model */

$this->title = 'Educational Plan';
$this->params['breadcrumbs'][] = ['label' => 'Educational Plan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form-plans', ['model' => $model]) ?>
