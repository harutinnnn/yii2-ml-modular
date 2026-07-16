<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\modules\journal\models\JournalSectionsForm $model */

$this->title = 'Update Journal Sections: ' . $model->journalSections->id;
$this->params['breadcrumbs'][] = ['label' => 'Journal Sections', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->journalSections    ->id, 'url' => ['view', 'id' => $model->journalSections->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= $this->render('_form', [
    'model' => $model,
    'journals' => $journals ?? []
]) ?>
