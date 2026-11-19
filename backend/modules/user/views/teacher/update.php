<?php

/** @var yii\web\View $this */
/** @var common\models\Student $model */

$this->title = 'Update Staff: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Students', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>
