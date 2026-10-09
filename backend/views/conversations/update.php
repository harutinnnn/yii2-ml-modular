<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Conversations $model */

$this->title = 'Update Conversations: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>


<?= $this->render('_form', [
        'model' => $model,
]) ?>

