<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\JournalArticles $model */

$this->title = 'Create Journal article';
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Journal Articles', 'url' => ['articles', 'id' => $journalId ?? 0,'number_id' => $number_id]];
$this->params['breadcrumbs'][] = $this->title;
?>


<?= $this->render('_article_form', [
    'model' => $model,
    'journalId' => $journalId ?? 0,
    'number_id' => $number_id ?? 0
]) ?>
