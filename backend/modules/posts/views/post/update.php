<?php

/** @var yii\web\View $this */
/** @var backend\modules\posts\models\PostForm $model */

$this->title = 'Update Post #' . $model->post?->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
