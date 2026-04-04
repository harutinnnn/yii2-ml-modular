<?php

use yii\helpers\Html;

$this->title = 'View Menu Item #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

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
        <p><strong>Show In Menu:</strong> <?= $model->show_in_menu ? 'Yes' : 'No' ?></p>
        <p><strong>Position:</strong> <?= Html::encode($model->position) ?></p>
        <p><strong>Content:</strong> <?= $model->content ? Html::encode($model->content->getDisplayTitle()) : 'None' ?></p>
        <p><strong>Section:</strong> <?= $model->section ? Html::encode($model->section->title) : 'None' ?></p>
        <p><strong>URL:</strong> <?= Html::encode($model->url) ?></p>
        <?php if ($model->image): ?><p><strong>Image:</strong><br><img src="<?= Html::encode($model->image) ?>" alt="" style="max-height: 90px;"></p><?php endif; ?>
        <?php if ($model->header_image): ?><p><strong>Header Image:</strong><br><img src="<?= Html::encode($model->header_image) ?>" alt="" style="max-height: 90px;"></p><?php endif; ?>
        <?php foreach ($model->translations as $translation): ?>
            <hr>
            <h5><?= Html::encode(strtoupper($translation->lang)) ?>: <?= Html::encode($translation->title) ?></h5>
            <p><strong>Meta Title:</strong> <?= Html::encode($translation->meta_title) ?></p>
            <p><strong>Meta Description:</strong> <?= nl2br(Html::encode($translation->meta_desc)) ?></p>
            <p><strong>Meta Keywords:</strong> <?= nl2br(Html::encode($translation->meta_keywords)) ?></p>
            <div><strong>Description:</strong><br><?= $translation->description ?></div>
        <?php endforeach; ?>
    </div>
</div>
