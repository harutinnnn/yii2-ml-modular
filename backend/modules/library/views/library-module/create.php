<?php

/** @var yii\web\View $this */
/** @var backend\modules\library\models\LibraryModuleForm $model */

$this->title = 'Create Library Module';
$this->params['breadcrumbs'][] = ['label' => 'Library Modules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model,'menuItem' => $menuItem ?? []]) ?>