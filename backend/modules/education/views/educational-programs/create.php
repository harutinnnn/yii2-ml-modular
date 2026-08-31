<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationalProgramsForm $model */

$this->title = 'Educational Programs';
$this->params['breadcrumbs'][] = ['label' => 'Educational Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'levels' => $levels ?? []]) ?>
