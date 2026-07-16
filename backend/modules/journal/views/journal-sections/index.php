<?php

use common\models\JournalSections;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalSectionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Journal Sections';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-sections-index">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Journal Section', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

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
                            'attribute' => 'journal_id',
                            'filter' => $journals ?? [],
                            'value' => static fn($model) => $journals[$model->journal_id] ?? '-',
                    ],
                    [
                            'attribute' => 'journal_section_type',
                            'filter' => \common\models\JournalSections::getTypesLabels(),
                            'value' => static fn($model) => $model->getTypesLabels()[$model->journal_section_type],
                    ],
                    [
                            'attribute' => 'status',
                            'filter' => \common\models\JournalSections::statusOptions(),
                            'value' => static fn($model) => $model->statusOptions()[$model->status],
                    ],
                    [
                            'class' => ActionColumn::class,
                            'header' => 'Actions',
                            'template' => '{update} {delete}',
                            'contentOptions' => ['class' => 'text-nowrap'],
                            'buttons' => [
                                    'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm mr-1']),
                                    'delete' => static fn($url, $model) => Html::a('Remove', ['delete', 'id' => $model->id], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                    ]),
                            ],
                    ],
            ],
    ]); ?>


</div>
