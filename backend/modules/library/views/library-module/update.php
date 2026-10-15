<?php

/** @var yii\web\View $this */
/** @var backend\modules\library\models\LibraryModuleForm $model */

$this->title = 'Update Library Module #' . $model->library_module?->id;
$this->params['breadcrumbs'][] = ['label' => 'Library Module', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'menuItem' => $menuItem ?? []]) ?>
