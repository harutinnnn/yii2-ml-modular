<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'View user #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= $model->full_name ?> - <?= $model->email ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>


        <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                        'email',
                        'full_name',
                        [
                                'attribute' => 'status',
                                'value' => static fn($model) => \common\models\Applicant::statusOptions()[$model->status],
                        ],
                        [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                ],
        ]) ?>

    </div>
</div>
