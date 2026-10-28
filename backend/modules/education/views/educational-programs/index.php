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
        <?= Html::a('Create Educational Programs', ['create'], ['class' => 'btn btn-primary']) ?>
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
                                    'attribute' => 'education_level',
                                    'filter' => $levels ?? [],
                                    'value' => static fn($model) => $levels[$model->education_level] ?? '-',
                            ],
                            [
                                    'attribute' => 'continuing_education',
                                    'filter' => [0 => 'No', 1 => 'Yes'],
                                    'value' => static fn($model) => $model->continuing_education ? 'Yes' : 'No',
                            ],
                            [
                                    'attribute' => 'title',
                                    'value' => static fn($model) => Html::img($model->getTranslation('en')->img, ['class' => 'img-thumbnail', 'style' => 'height:150px']),
                                    'format' => 'html',
                            ],
                            [
                                    'attribute' => 'status',
                                    'filter' => EducationalPrograms::statusOptions(),
                                    'value' => static fn($model) => $model->getStatusLabel(),
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{update} {plans} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'plans' => static fn($url, $model) => Html::a('Plans', ['plans', 'programId' => $model->id], ['class' => 'btn btn-warning btn-sm mr-1']),
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
