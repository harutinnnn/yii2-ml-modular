<?php

use common\models\JournalAuthors;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalAuthorsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Journal Authors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-authors-index">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => ['class' => 'table table-hover mb-0'],
            'layout' => "{items}\n<div class=\"card-footer clearfix\">{summary}{pager}</div>",
            'columns' => [
                    'first_name',
                    'last_name',
                    'bio:ntext',
                    'img',
                    [
                            'class' => ActionColumn::className(),
                            'urlCreator' => function ($action, JournalAuthors $model, $key, $index, $column) {
                                return Url::toRoute([$action, 'id' => $model->id]);
                            }
                    ],
            ],
    ]); ?>


</div>
