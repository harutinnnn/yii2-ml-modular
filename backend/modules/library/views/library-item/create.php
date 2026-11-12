<?php

/** @var yii\web\View $this */
/** @var backend\modules\library\models\LibraryItemForm $model */

$this->title = 'Create Library item';
$this->params['breadcrumbs'][] = ['label' => 'Library items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'libraryModules' => $libraryModules ?? []]) ?>