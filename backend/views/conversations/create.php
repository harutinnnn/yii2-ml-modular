<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Conversations $model */

$this->title = 'Create Conversations';
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>