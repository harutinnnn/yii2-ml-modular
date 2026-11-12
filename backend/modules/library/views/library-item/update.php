<?php

/** @var yii\web\View $this */
/** @var backend\modules\library\models\LibraryItemForm $model */

$this->title = 'Update Library item #' . $model->library_item?->id;
$this->params['breadcrumbs'][] = ['label' => 'Library item', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'libraryModules' => $libraryModules ?? []]) ?>
