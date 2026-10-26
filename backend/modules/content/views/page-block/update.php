<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\PageBlockForm $model */

$this->title = 'Update Page block #' . $model->page_block?->id;
$this->params['breadcrumbs'][] = ['label' => 'Page block', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
