<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\JournalSections $model */

$this->title = 'Create Journal Sections';
$this->params['breadcrumbs'][] = ['label' => 'Journal Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'journals' => $journals ?? []
]) ?>
