<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\PageBlockForm $model */

$this->title = 'Create Page block';
$this->params['breadcrumbs'][] = ['label' => 'Page block', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
