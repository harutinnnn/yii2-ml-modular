<?php

/** @var yii\web\View $this */
/** @var backend\modules\faculties\models\ChairsForm $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Chair';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="chair-content-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Chair', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover mb-0'],
                    'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
                    'columns' => [
                            [
                                    'attribute' => 'title',
                                    'value' => static fn($model) => $model->getDisplayTitle(),
                            ],
                            [
                                    'attribute' => 'faculty_id',
                                    'value' => function ($model) use ($faculties) {
                                        return $faculties[$model->faculty_id] ?? '-';
                                    },
                            ],
                            'pos',
                            [
                                    'attribute' => 'status',
                                    'filter' => \common\models\Chairs::statusOptions(),
                                    'value' => static fn($model) => $model->getStatusLabel(),
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{view} {update} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'view' => static fn($url, $model) => Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm mr-1']),
                                            'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'delete' => static fn($url, $model) => Html::a('Remove', ['delete', 'id' => $model->id], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Are you sure you want to delete this item?',
                                            ]),
                                    ],
                            ],
                    ],
            ]) ?>
        </div>
    </div>
</div>
