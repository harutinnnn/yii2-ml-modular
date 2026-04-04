<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\ContentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Content';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Content', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-hover mb-0'],
                'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'title',
                        'value' => static fn($model) => $model->getDisplayTitle(),
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => \common\models\Content::statusOptions(),
                        'value' => static fn($model) => $model->getStatusLabel(),
                    ],
                    [
                        'class' => ActionColumn::class,
                        'header' => 'Actions',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
