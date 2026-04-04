<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\ContentForm $model */

$this->title = 'Create Content';
$this->params['breadcrumbs'][] = ['label' => 'Content', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
