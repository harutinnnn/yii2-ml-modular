<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalArticlesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Journal articles';
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-article-index">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Article', ['create-article', 'journalId' => $journalId ?? 0], ['class' => 'btn btn-primary']) ?>
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
                            'format' => 'raw',
                    ],
                    [
                            'attribute' => 'status',
                            'filter' => \common\models\JournalArticles::optsStatus(),
                            'value' => static fn($model) => $model->getStatusLabel(),
                    ],
                    [
                            'class' => ActionColumn::class,
                            'header' => 'Actions',
                            'template' => '{update} {view} {delete}',
                            'contentOptions' => ['class' => 'text-nowrap'],
                            'buttons' => [
                                    'view' => static fn($url, $model) => Html::a('View', ['article-view', 'id' => $model->id, 'journalId' => $model->journal_id], ['class' => 'btn btn-info btn-sm mr-1']),
                                    'update' => static fn($url, $model) => Html::a('Edit', ['update-article', 'id' => $model->id, 'journalId' => $model->journal_id], ['class' => 'btn btn-success btn-sm mr-1']),
                                    'delete' => static fn($url, $model) => Html::a('Remove', ['article-delete', 'id' => $model->id, 'journalId' => $model->journal_id], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                    ]),
                            ],
                    ],
            ],
    ]); ?>


</div>
