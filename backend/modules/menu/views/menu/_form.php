<?php

use common\models\Menu;
use common\widgets\ckeditor\CkEditor;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$languages = $model->getLanguages();
$sectionOptions = $model::sectionOptions();
$parentOptions = $model::parentOptions($model->menu?->id);
$slugUrl = Url::to(['slugify']);
$menuId = $model->menu?->id;
$slugUrlJson = Json::htmlEncode($slugUrl);
$menuIdJson = Json::htmlEncode($menuId);
?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"><?= $form->field($model, 'status')->dropDownList(Menu::statusOptions()) ?></div>
                <div class="col-md-3"><?= $form->field($model, 'show_in_menu')->dropDownList(Menu::booleanOptions()) ?></div>
                <div class="col-md-2"><?= $form->field($model, 'position')->input('number') ?></div>
                <div class="col-md-4"><?= $form->field($model, 'content_id')->dropDownList($model::contentOptions()) ?></div>
            </div>
            <div class="row">
                <div class="col-md-4"><?= $form->field($model, 'url')->textInput(['maxlength' => true, 'id' => 'menu-url']) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'section_id')->dropDownList($sectionOptions) ?></div>
                <div class="col-md-4"><?= $form->field($model, 'parent_id')->dropDownList($parentOptions) ?></div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'imageFile')->fileInput() ?>
                    <?php if ($model->image): ?><p class="mb-0"><img src="<?= Html::encode($model->image) ?>" alt="" style="max-height: 70px;"></p><?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'headerImageFile')->fileInput() ?>
                    <?php if ($model->header_image): ?><p class="mb-0"><img src="<?= Html::encode($model->header_image) ?>" alt="" style="max-height: 70px;"></p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($languages as $index => $language): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" data-toggle="pill" href="#menu-lang-<?= Html::encode($language->code) ?>" role="tab">
                            <?= Html::encode(strtoupper($language->code)) ?>: <?= Html::encode($language->name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $index => $language): ?>
                    <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" id="menu-lang-<?= Html::encode($language->code) ?>" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6"><?= $form->field($model, "translations[{$language->code}][title]")->label('Title')->textInput([
                                'maxlength' => true,
                                'class' => 'form-control js-menu-title',
                                'data-lang' => $language->code,
                            ]) ?></div>
                            <div class="col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><?= $form->field($model, "translations[{$language->code}][meta_title]")->label('Meta Title')->textInput(['maxlength' => true]) ?></div>
                            <div class="col-md-6"></div>
                        </div>
                        <?= $form->field($model, "translations[{$language->code}][meta_desc]")->label('Meta Description')->textarea(['rows' => 2]) ?>
                        <?= $form->field($model, "translations[{$language->code}][meta_keywords]")->label('Meta Keywords')->textarea(['rows' => 2]) ?>
                        <?= $form->field($model, "translations[{$language->code}][description]")->label('Description')->widget(CkEditor::class, [
                            'elfinderController' => ['elfinder', 'filter' => 'image', 'lang' => 'en'],
                            'clientOptions' => [
                                'height' => 220,
                                'toolbar' => [
                                    ['name' => 'document', 'items' => ['Source']],
                                    ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
                                    ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Underline', 'RemoveFormat']],
                                    ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', 'Blockquote']],
                                    ['name' => 'links', 'items' => ['Link', 'Unlink']],
                                    ['name' => 'insert', 'items' => ['Image', 'Table', 'HorizontalRule', 'SpecialChar']],
                                    ['name' => 'styles', 'items' => ['Format']],
                                ],
                            ],
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->menu === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$this->registerJs(<<<JS
(function () {
    const slugUrl = {$slugUrlJson};
    const menuId = {$menuIdJson};

    async function updateSlug(titleInput) {
        const urlInput = document.getElementById('menu-url');
        if (!urlInput) {
            return;
        }

        const title = titleInput.value.trim();
        if (!title) {
            return;
        }

        const params = new URLSearchParams({ title: title });
        if (menuId !== null) {
            params.set('menuId', menuId);
        }

        const response = await fetch(slugUrl + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (!response.ok) {
            return;
        }

        const data = await response.json();
        if (data.slug) {
            urlInput.value = data.slug;
        }
    }

    document.querySelectorAll('.js-menu-title').forEach(function (input) {
        if (input.dataset.lang === 'en') {
            input.addEventListener('change', function () {
                updateSlug(input);
            });
        }
    });
})();
JS); ?>
