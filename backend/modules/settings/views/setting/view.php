<?php

/** @var yii\web\View $this */
/** @var common\models\Setting $model */

use yii\helpers\Html;
use yii\helpers\StringHelper;

$this->title = 'View Setting: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="setting-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($model->title) ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Title:</strong> <?= Html::encode($model->title) ?></p>
            <p><strong>Key:</strong> <?= Html::encode($model->key) ?></p>
            <p><strong>Value:</strong></p>
            <div class="border rounded p-3 bg-light"><?= nl2br(Html::encode(StringHelper::truncate((string) $model->value, 10000, '...'))) ?></div>
        </div>
    </div>
</div>
