<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationLevelsForm $model */

$this->title = 'Update Education Level #' . $model->education_levels?->id;
$this->params['breadcrumbs'][] = ['label' => 'Education Level', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
