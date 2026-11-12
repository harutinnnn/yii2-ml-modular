<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\NewsTagsForm $model */

$this->title = 'Create Tag';
$this->params['breadcrumbs'][] = ['label' => 'News Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
