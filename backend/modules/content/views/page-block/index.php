<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\PageBlockSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Page blocks';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Page blocks', ['create'], ['class' => 'btn btn-primary']) ?>
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
                                    'value' => static fn($model) => \common\models\TranslationModel::getDisplayTitle($model),
                            ],
                            [
                                    'attribute' => 'pre_title',
                                    'value' => static fn($model) => \common\models\TranslationModel::getDisplayTitle($model, 'pre_title'),
                            ],
                            [
                                    'attribute' => 'type',
                                    'filter' => \common\models\PageBlock::typeOptions(),
                                    'value' => static fn($model) => $model->getType($model->type),
                            ],
                            [
                                    'attribute' => 'status',
                                    'filter' => \common\models\PageBlock::statusOptions(),
                                    'value' => static fn($model) => \common\components\StatusList::getStatusLabel($model->status),
                            ],
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{update} {block_items} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'block_items' => static fn($url, $model) => Html::a(
                                                    '<i class="fas fa-th-large"></i> &nbsp;&nbsp; Block items',
                                                    ['//content/page-block-item/index', 'id' => $model->id, 'page_block_id' => $model->id],
                                                    ['class' => 'btn btn-info btn-sm mr-1']
                                            ),
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
