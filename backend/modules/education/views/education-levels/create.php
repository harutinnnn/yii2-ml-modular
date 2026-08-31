<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationLevelsForm $model */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Education Levels', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
