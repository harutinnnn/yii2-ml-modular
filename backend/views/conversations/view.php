<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Conversations $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Conversations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">
    <div class="card">

        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>


        <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                        'id',
                        'type',
                        'title',
                        [
                                'attribute' => 'created_by',
                                'value' => function ($model) {
                                    $user = \common\models\User::findOne($model->created_by);
                                    return ($user->additional->first_name ?? "") . ' ' . ($user->additional->last_name ?? "");
                                },
                        ],
                        'created_at',
                ],
        ]) ?>

    </div>
</div>
