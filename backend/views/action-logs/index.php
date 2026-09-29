<?php

use common\components\EntityTypes;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var backend\models\ActionLogsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Action Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="applicant-index">

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
                                    'attribute' => 'user_id',
                                    'label' => 'User made changes',
                                    'value' => static function ($model) {
                                        $userData = $model->user->userAdditionalData ?? null;
                                        return ($userData->first_name ?? '-') . ' ' . ($userData->last_name ?? '-');
                                    },
                            ],
                            [
                                    'attribute' => 'action',
                                    'filter' => \common\models\ActionLogs::getActions(),
                                    'filterInputOptions' => [
                                            'class' => 'form-control',
                                            'prompt' => 'All',
                                    ],
                            ],
                            [
                                    'attribute' => 'entity_type',
                                    'filter' => EntityTypes::getEntityTypes(),
                                    'filterInputOptions' => [
                                            'class' => 'form-control',
                                            'prompt' => 'All',
                                    ],
                            ],
                            'description',
                            'ip_address',
                            'request_method',
                            [
                                    'attribute' => 'created_at',
                                    'format' => ['datetime', 'php:Y-m-d H:i:s'],
                                    'filter' => DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'created_at',
                                            'dateFormat' => 'yyyy-MM-dd',
                                            'options' => [
                                                    'class' => 'form-control',
                                                    'autocomplete' => 'off',
                                                    'placeholder' => 'Select date',
                                            ],
                                    ]),
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{view}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'view' => static fn($url, $model) => Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm mr-1']),
                                    ],
                            ],
                    ],
            ]); ?>
        </div>
    </div>

</div>
