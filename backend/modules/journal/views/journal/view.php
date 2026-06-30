<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Journal $model */

$this->title = 'View Journal #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="journal-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($model->getDisplayTitle()) ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">

            <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>
            <p><strong>Status:</strong> <?= Html::encode($model->getStatusLabel()) ?></p>

            <?php foreach ($model->translations as $translation): ?>
                <hr>
                <h5><?= Html::encode(strtoupper($translation->lang)) ?>: <?= Html::encode($translation->title) ?></h5>
                <div><?= $translation->description ?></div>
            <?php endforeach; ?>

            <hr>
            <p><strong>Authors: </strong>
                <?= isset($journal_authors) && !empty($journal_authors) ? implode(', ', $journal_authors) : '-' ?>
            </p>

        </div>

    </div>
</div>
