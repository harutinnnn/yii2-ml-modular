<?php

/** @var yii\web\View $this */
/** @var backend\modules\news\models\NewsForm $model */

$this->title = 'Create News';
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', ['model' => $model, 'categories' => $categories ?? []]) ?>
