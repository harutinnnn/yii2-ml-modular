<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalArticleForm $model */


$this->title = 'Update Content #' . $model->journalArticle?->id;
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Numbers', 'url' => ['numbers', 'id' => $journalId ?? 0, 'number_id' => $number_id ?? 0]];
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['articles', 'id' => $journalId ?? 0, 'number_id' => $number_id ?? 0]];
$this->params['breadcrumbs'][] = $this->title;


?>

<?= $this->render('_article_form', [
    'model' => $model,
    'journalId' => $journalId ?? 0,
    'number_id' => $number_id ?? 0
]) ?>

