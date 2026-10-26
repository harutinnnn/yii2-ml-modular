<?php

/** @var yii\web\View $this */
/** @var backend\modules\content\models\PageBlockItemSearch $searchModel */

/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Page block items';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Page blocks', ['create', 'page_block_id' => $page_block_id ?? 0], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Back', ['//content/page-block/index'], ['class' => 'btn btn-info']) ?>
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
                            'pos',
                            [
                                    'class' => ActionColumn::class,
                                    'header' => 'Actions',
                                    'template' => '{view} {update} {delete}',
                                    'contentOptions' => ['class' => 'text-nowrap'],
                                    'buttons' => [
                                            'view' => static fn($url, $model) => Html::a('View', ['view', 'id' => $model->id,'page_block_id' => $model->page_block_id], ['class' => 'btn btn-info btn-sm mr-1']),
                                            'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id,'page_block_id' => $model->page_block_id], ['class' => 'btn btn-success btn-sm mr-1']),
                                            'delete' => static fn($url, $model) => Html::a('Remove', ['delete', 'id' => $model->id,'page_block_id' => $model->page_block_id], [
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
