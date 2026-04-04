<?php

/** @var yii\web\View $this */
/** @var common\models\Language $model */

use yii\helpers\Html;

$this->title = 'View Language: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="language-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($model->name) ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <p><strong>Code:</strong> <?= Html::encode($model->code) ?></p>
            <p><strong>Name:</strong> <?= Html::encode($model->name) ?></p>
            <p><strong>Default:</strong> <?= $model->is_default ? 'Yes' : 'No' ?></p>
            <p><strong>Active:</strong> <?= $model->is_active ? 'Yes' : 'No' ?></p>
            <p><strong>Sort Order:</strong> <?= Html::encode($model->sort_order) ?></p>
        </div>
    </div>
</div>
