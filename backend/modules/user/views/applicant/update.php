<?php

/** @var yii\web\View $this */
/** @var common\models\Applicant $model */

$this->title = 'Update Applicant: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Applicants', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>
