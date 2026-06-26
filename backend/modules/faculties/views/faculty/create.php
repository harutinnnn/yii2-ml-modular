<?php

/** @var yii\web\View $this */
/** @var backend\modules\faculties\models\FacultiesForm $model */

$this->title = 'Create Faculties';
$this->params['breadcrumbs'][] = ['label' => 'Faculties', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
