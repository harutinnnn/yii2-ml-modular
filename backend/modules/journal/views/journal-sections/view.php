<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\JournalSections $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Journal Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="journal-sections-view">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0"><?= $model->first_name . ' ' . $model->last_name ?></h3>
            <div>
                <?= Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-success btn-sm']) ?>
                <?= Html::a('Back', ['index'], ['class' => 'btn btn-secondary btn-sm']) ?>
            </div>
        </div>



        <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                        'id',
                        'journal_id',
                        'journal_section_type',
                        'status',
                ],
        ]) ?>
    </div>

</div>
