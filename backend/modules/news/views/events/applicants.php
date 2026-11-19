<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\EventsSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use common\components\ExportTools;
use common\components\StatusList;
use common\models\Events;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Event applicants';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Back', ['/news/events'], ['class' => 'btn btn-primary']) ?>
        <?= ExportTools::export($dataProvider) ?>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <?= GridView::widget(config: [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'tableOptions' => ['class' => 'table table-hover mb-0'],
                    'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
                    'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'first_name',
                            'last_name',
                            [
                                    'attribute' => 'event',
                                    'value' => static fn($model) => $model->event->getDisplayTitle(),
                            ],
                            [
                                    'attribute' => 'status',
                                    'filter' => StatusList::statusOptionsShort(),
                                    'value' => static fn($model) => StatusList::getStatusLabel($model->status),
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{update} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'update' => static fn($url, $model) => Html::a('Edit', ['update_applicant', 'id' => $model->id, 'event_id' => $model->event_id], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'delete' => static fn($url, $model) => Html::a('Remove', ['delete_applicant', 'id' => $model->id, 'event_id' => $model->event_id], [
                                                    'class' => 'btn btn-danger btn-sm',
                                                    'data-method' => 'post',
                                                    'data-confirm' => 'Are you sure you want to delete this item?',
                                            ])
                                    ],
                            ],
                    ],
            ]) ?>
        </div>
    </div>
</div>
