<?php

use common\components\I18n;
use common\models\Language;
use common\models\TranslationModel;
use yii\helpers\ArrayHelper;

$content = $this->context->pageData['content'];
$menuObj = $this->context->pageData['menuObj'];
$languages = ArrayHelper::map(Language::find()->all(), 'code', 'name');
?>


<section class="inner-section">
    <div class="wrap inner-grid-2">
        <div>

            <h2 class="display-inner"><?= I18n::translate('no_content_with_current_language') ?></h2>

            <div class="body-copy">
                <?= I18n::translate('no_content_with_current_language_text') ?>
                <p>
                    <?= I18n::translate('for_example') ?>`

                    <?php foreach ($languages as $langCode => $landTitle): ?>
                        <?php if ($langCode !== Yii::$app->globalData->lang): ?>
                            <?php
                            $url = trim(Yii::$app->request->url, '/');
                            $url = explode('/', $url);
                            $url[0] = $langCode;
                            ?>
                            <a href="/<?= implode('/', $url) ?>" class="no-content-lang-url"><?= $landTitle ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </p>
            </div>
        </div>

        <?php if (isset($sideMenuList) && !empty($sideMenuList)): ?>
            <aside>
                <div class="meta-label">Արագ հղումներ</div>
                <div class="link-list">
                    <?php foreach ($sideMenuList as $sideMenu): ?>
                        <a href="<?= $sideMenu['url'] ?>"><span><?= $sideMenu['title'] ?></span></a>
                    <?php endforeach; ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>
</section>
