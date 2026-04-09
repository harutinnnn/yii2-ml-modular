<?php

/** @var yii\web\View $this */
/** @var common\models\Post $model */

use common\helpers\EditorJsHelper;
use yii\helpers\Html;

$this->title = 'View Post #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-view">
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
            <?php if ($model->image): ?>
                <p><strong>Image:</strong></p>
                <p><img src="<?= Html::encode($model->image) ?>" alt="" style="max-width: 240px; max-height: 160px;"></p>
            <?php endif; ?>

            <?php foreach ($model->translations as $translation): ?>
                <hr>
                <h5><?= Html::encode(strtoupper($translation->lang)) ?>: <?= Html::encode($translation->title) ?></h5>
                <div><?= EditorJsHelper::render($translation->text) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
