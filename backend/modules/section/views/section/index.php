<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Sections';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="section-index">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Section', ['create'], ['class' => 'btn btn-primary']) ?>
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
                    'title',
                    'key',
                    'position',
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view} {update} {delete}',
                        'contentOptions' => ['class' => 'text-nowrap'],
                        'buttons' => [
                            'view' => static fn($url, $model) => Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-info btn-sm mr-1']),
                            'update' => static fn($url, $model) => Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm mr-1']),
                            'delete' => static fn($url, $model) => Html::a('Remove', ['delete', 'id' => $model->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'data-confirm' => 'Are you sure you want to delete this item?']),
                        ],
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>
