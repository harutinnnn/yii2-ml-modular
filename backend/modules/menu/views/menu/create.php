<?php
$this->title = 'Create Menu Item';
$this->params['breadcrumbs'][] = ['label' => 'Menu', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', ['model' => $model,'page_blocks' => $page_blocks ?? []]) ?>
