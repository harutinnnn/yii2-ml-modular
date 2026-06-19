<?php

/** @var yii\web\View $this */
/** @var backend\modules\statuses\models\StatusesForm $model */

$this->title = 'Update Status #' . $model->statuses?->id;
$this->params['breadcrumbs'][] = ['label' => 'Status', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
