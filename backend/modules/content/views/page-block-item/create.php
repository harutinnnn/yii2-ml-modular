<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\PageBlockItemForm $model */

$this->title = 'Create Page block item';
$this->params['breadcrumbs'][] = ['label' => 'Page block item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model]) ?>
