<?php

/** @var yii\web\View $this */
/** @var backend\modules\education\models\EducationalProgramsSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use common\models\EducationalPrograms;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Educational Programs';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Educational Programs', ['plans-create', 'programId' => $programId ?? 0], ['class' => 'btn btn-primary']) ?>
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
                            [
                                    'attribute' => 'title',
                                    'value' => static fn($model) => $model->getDisplayTitle(),
                            ],
                            [
                                    'attribute' => 'status',
                                    'filter' => \common\models\EducationPlan::statusOptions(),
                                    'value' => static fn($model) => $model->getStatusLabel(),
                            ],
                            'pos',
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{update} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'update' => static fn($url, $model) => Html::a('Edit', ['plans-update', 'id' => $model->id,'programId' => $programId ?? 0], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'delete' => static fn($url, $model) => Html::a('Remove', ['plans-delete', 'id' => $model->id,'programId' => $programId ?? 0], [
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
