<?php

use common\models\JournalNumbers;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalNumbersSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Journal Numbers';
$this->params['breadcrumbs'][] = ['label' => 'Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-numbers-index">


    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Journal number', ['create-number', 'journal_id' => $journal_id ?? 0], ['class' => 'btn btn-primary']) ?>
    </div>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-hover mb-0'],
            'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
            'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'year',
                    'number',
                    [
                            'attribute' => 'status',
                            'filter' => \common\models\Post::statusOptions(),
                            'value' => static fn($model) => $model->getStatusLabel(),
                    ],
                    [
                            'class' => ActionColumn::class,
                            'header' => 'Actions',
                            'template' => '{articles} {delete}',
                            'contentOptions' => ['class' => 'text-nowrap'],
                            'buttons' => [
                                    'articles' => static fn($url, $model) => Html::a('<i class="fas fa-newspaper"></i>&nbsp;&nbsp; Articles', ['articles', 'id' => $model->journal_id, 'number_id' => $model->id], ['class' => 'btn btn-primary btn-sm mr-1']),
                                    'delete' => static fn($url, $model) => Html::a('Remove', ['delete-journal-number', 'id' => $model->id, 'journal_id' => $model->journal_id], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Are you sure you want to delete this item?',
                                    ]),
                            ],
                    ],
            ],
    ]); ?>


</div>
