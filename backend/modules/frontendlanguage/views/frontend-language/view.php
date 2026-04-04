<?php

/** @var yii\web\View $this */
/** @var common\models\FrontendLanguage $model */

use yii\helpers\Html;

$this->title = 'View Frontend Language Item #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Frontend Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="frontend-language-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= Html::encode($model->getDisplayKey()) ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> <?= Html::encode($model->id) ?></p>
            <p><strong>Key:</strong> <?= Html::encode($model->key) ?></p>
            <p><strong>Status:</strong> <?= Html::encode($model->getStatusLabel()) ?></p>
            <p><strong>Type:</strong> <?= Html::encode($model->getTypeLabel()) ?></p>

            <?php foreach ($model->translations as $translation): ?>
                <hr>
                <h5><?= Html::encode(strtoupper($translation->lang)) ?></h5>
                <div><?= $model->type === \common\models\FrontendLanguage::TYPE_CONTENT ? $translation->text : nl2br(Html::encode($translation->text)) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
