<?php

use common\helpers\EditorJsHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Journal $model */

$this->title = 'View Journal #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Journal Articles', 'url' => ['articles','id' => $journalId??0]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="journal-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($model->getDisplayTitle()) ?></h3>
            <div>
                <?= Html::a('Edit', ['update-article', 'id' => $model->id,'journalId' => $model->journal_id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['articles', 'id' => $journalId ?? 0], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">

            <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>
            <p><strong>Status:</strong> <?= Html::encode($model->getStatusLabel()) ?></p>

            <?php foreach ($model->translations as $translation): ?>
                <hr>
                <h5><?= Html::encode(strtoupper($translation->lang)) ?>: <?= Html::encode($translation->title) ?></h5>
                <div><?= EditorJsHelper::render($translation->description) ?></div>
            <?php endforeach; ?>

        </div>
    </div>
</div>
