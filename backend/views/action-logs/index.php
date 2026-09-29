<?php

use common\models\ActionLogs;
use common\models\UserAdditionalData;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

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
                            'action',
                            'entity_type',
//                            'entity_id',
                            'description',
                        //'old_values',
                        //'new_values',
                        //'ip_address',
                        //'user_agent',
                        //'request_method',
                        //'request_url:url',
                            'created_at',
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
