<?php

use yii\helpers\Html;

$this->title = 'View Section: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0"><?= Html::encode($model->title) ?></h3>
        <div>
            <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
            <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
        </div>
    </div>
    <div class="card-body">
        <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>
        <p><strong>Title:</strong> <?= Html::encode($model->title) ?></p>
        <p><strong>Key:</strong> <?= Html::encode($model->key) ?></p>
        <p><strong>Position:</strong> <?= Html::encode($model->position) ?></p>
    </div>
</div>
