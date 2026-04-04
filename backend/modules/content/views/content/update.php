<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\ContentForm $model */

$this->title = 'Update Content #' . $model->content?->id;
$this->params['breadcrumbs'][] = ['label' => 'Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
