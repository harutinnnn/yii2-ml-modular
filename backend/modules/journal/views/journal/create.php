<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Journal $model */

$this->title = 'Create Journal';
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_form', [
    'model' => $model,
    'authors' => $authors ?? []
]) ?>
