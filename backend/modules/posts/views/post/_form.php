<?php

/** @var yii\web\View $this */
/** @var backend\modules\posts\models\PostForm $model */

use common\helpers\EditorJsHelper;
use common\models\Post;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$languages = $model->getLanguages();
$editorConfigs = [];
$uploadImageUrl = Url::to(['upload-image']);
$csrfParam = Yii::$app->request->csrfParam;
$csrfToken = Yii::$app->request->csrfToken;
?>

<div class="post-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card card-primary">
        <div class="card-body">
            <?= $form->field($model, 'status')->dropDownList(Post::statusOptions()) ?>
            <?= $form->field($model, 'imageFile')->fileInput() ?>
            <?php if ($model->image): ?>
                <p class="mb-0"><img src="<?= Html::encode($model->image) ?>" alt="" style="max-height: 100px;"></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" role="tablist">
                <?php foreach ($languages as $index => $language): ?>
                    <li class="nav-item">
                        <a
                            class="nav-link <?= $index === 0 ? 'active' : '' ?>"
                            id="lang-tab-<?= Html::encode($language->code) ?>"
                            data-toggle="pill"
                            href="#lang-pane-<?= Html::encode($language->code) ?>"
                            role="tab"
                            aria-controls="lang-pane-<?= Html::encode($language->code) ?>"
                            aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                        >
                            <?= Html::encode(strtoupper($language->code)) ?>: <?= Html::encode($language->name) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <?php foreach ($languages as $index => $language): ?>
                    <div
                        class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>"
                        id="lang-pane-<?= Html::encode($language->code) ?>"
                        role="tabpanel"
                        aria-labelledby="lang-tab-<?= Html::encode($language->code) ?>"
                    >
                        <?= $form->field($model, "translations[{$language->code}][title]")
                            ->label("Title ({$language->name})")
                            ->textInput([
                                'maxlength' => true,
                                'placeholder' => "Title in {$language->name}",
                            ]) ?>

                        <?= $form->field($model, "translations[{$language->code}][text]")
                            ->label("Text ({$language->name})")
                            ->hiddenInput(['id' => "post-text-{$language->code}"])
                            ->label("Text ({$language->name})") ?>
                        <div
                            class="border rounded p-3 bg-white js-editorjs-holder"
                            id="editorjs-<?= Html::encode($language->code) ?>"
                            style="min-height: 320px;padding-left: 60px !important;"
                        ></div>
                        <?php
                        $editorConfigs[] = [
                            'holderId' => "editorjs-{$language->code}",
                            'inputId' => "post-text-{$language->code}",
                            'data' => Json::decode(EditorJsHelper::encodeInitialData($model->translations[$language->code]['text'] ?? '')),
                        ];
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="card-footer">
            <?= Html::submitButton($model->post === null ? 'Create' : 'Update', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php
$editorConfigsJson = Json::htmlEncode($editorConfigs);
$uploadImageUrlJson = Json::htmlEncode($uploadImageUrl);
$csrfParamJson = Json::htmlEncode($csrfParam);
$csrfTokenJson = Json::htmlEncode($csrfToken);
$this->registerCss(<<<CSS
.ce-block__content,
.ce-toolbar__content {
    max-width: 100%;
}
.editorjs-image img {
    max-width: 100%;
    height: auto;
}
.codex-editorjs .ce-paragraph {
    line-height: 1.6;
}
CSS);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/header@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/list@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/quote@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/@editorjs/image@latest', ['position' => \yii\web\View::POS_END]);
$this->registerJs(<<<JS
(function () {
    const configs = {$editorConfigsJson};
    const uploadImageUrl = {$uploadImageUrlJson};
    const csrfParam = {$csrfParamJson};
    const csrfToken = {$csrfTokenJson};
    const editors = [];

    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        formData.append(csrfParam, csrfToken);

        return fetch(uploadImageUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(function (response) {
            return response.json();
        });
    }

    function createEditor(config) {
        const input = document.getElementById(config.inputId);
        if (!input) {
            return;
        }

        class YouTubeEmbed {
            static get toolbox() {
                return {
                    title: 'YouTube',
                    icon: '<svg width="17" height="15" viewBox="0 0 336 276"><path d="M336 70s-3-25-17-43c-16-20-34-20-42-21-60-5-149-5-149-5s-89 0-149 5c-8 1-26 1-42 21C23 45 20 70 20 70s-3 29-3 88v30c0 59 3 88 3 88s3 25 17 43c16 20 37 21 46 22 33 2 114 4 150 4s117-2 150-4c9-1 30-2 46-22 14-18 17-43 17-43s3-29 3-88v-30c0-59-3-88-3-88zM137 211V98l95 56-95 57z"/></svg>'
                };
            }

            static get isReadOnlySupported() {
                return true;
            }

            constructor({ data }) {
                this.data = data || {};
                this.container = document.createElement('div');
                this.container.className = 'youtube-embed';
            }

            render() {
                const wrapper = document.createElement('div');
                const input = document.createElement('input');
                input.className = 'form-control mb-3';
                input.placeholder = 'Enter YouTube video URL';
                input.value = this.data.url || '';
                input.addEventListener('change', (event) => {
                    this.data = { url: event.target.value };
                    this.refresh(wrapper, input);
                });

                this.refresh(wrapper, input);

                return wrapper;
            }

            refresh(wrapper, input) {
                wrapper.innerHTML = '';
                wrapper.appendChild(input);

                const videoId = this.extractVideoId(this.data.url || '');
                if (!videoId) {
                    return;
                }

                const iframe = document.createElement('iframe');
                iframe.src = 'https://www.youtube.com/embed/' + videoId;
                iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
                iframe.allowFullscreen = true;
                iframe.style.width = '100%';
                iframe.style.aspectRatio = '16 / 9';
                iframe.style.border = '0';
                wrapper.appendChild(iframe);
            }

            extractVideoId(url) {
                const regExp = /^.*(youtu.be\\/|v\\/|u\\/\\w\\/|embed\\/|watch\\?v=|\\&v=)([^#\\&\\?]*).*/;
                const match = url.match(regExp);
                return match && match[2].length === 11 ? match[2] : null;
            }

            save() {
                return this.data;
            }
        }

        const editor = new EditorJS({
            holder: config.holderId,
            data: config.data || { blocks: [] },
            minHeight: 280,
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: true,
                    config: { levels: [2, 3, 4], defaultLevel: 2 }
                },
                list: {
                    class: EditorjsList,
                    inlineToolbar: true
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true
                },
                delimiter: Delimiter,
                image: {
                    class: ImageTool,
                    config: {
                        uploader: {
                            uploadByFile(file) {
                                return uploadImage(file);
                            },
                            uploadByUrl(url) {
                                return Promise.resolve({
                                    success: 1,
                                    file: { url: url }
                                });
                            }
                        }
                    }
                },
                youtube: YouTubeEmbed
            }
        });

        editors.push({ editor, input });
    }

    configs.forEach(createEditor);

    const form = document.querySelector('.post-form form');
    if (!form) {
        return;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        for (const item of editors) {
            const output = await item.editor.save();
            item.input.value = JSON.stringify(output);
        }

        form.submit();
    });
})();
JS, \yii\web\View::POS_END);
?>
