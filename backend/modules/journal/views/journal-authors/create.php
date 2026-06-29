<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\JournalAuthors $model */

$this->title = 'Create Journal Authors';
$this->params['breadcrumbs'][] = ['label' => 'Journal Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
        'model' => $model,
]) ?>
