<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\JournalAuthors $model */

$this->title = 'Update Journal Authors: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Journal Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>