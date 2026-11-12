<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\NewsTagsForm $model */

$this->title = 'Update News Tag #' . $model->news_tags?->id;
$this->params['breadcrumbs'][] = ['label' => 'News tag', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
