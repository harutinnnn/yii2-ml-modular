<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalForm $model */


$this->title = 'Update Content #' . $model->journal?->id . ' | ' . $model->doi_prefix . '/' . $model->doi_suffix;
$this->params['breadcrumbs'][] = ['label' => 'Journal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', [
    'model' => $model,
    'authors' => $authors ?? []
]) ?>

