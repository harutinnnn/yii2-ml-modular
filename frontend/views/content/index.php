<?php
$content = $this->context->pageData['content'];
$menuObj = $this->context->pageData['menuObj'];
?>

<?php if (isset($content->image) && is_file(Yii::getAlias('@frontend/web' . $content->image))) : ?>
    <section class="inner-section">
        <div class="wrap profile-hero">
            <img alt="Ռեկտորի ուղերձ" class="profile-photo"
                 src="<?= $content->image ?>">

            <div>
                <?php if ($content?->getTranslation(Yii::$app->globalData->lang)->pre_title): ?>
                    <div class="eyebrow red"><?= $content?->getTranslation(Yii::$app->globalData->lang)->pre_title ?></div>
                <?php endif; ?>

                <h2 class="display-inner"><?= $content?->getTranslation(Yii::$app->globalData->lang)->title ?></h2>

                <?php if ($content?->getTranslation(Yii::$app->globalData->lang)->quote): ?>
                    <p class="quote-block">«<?= $content?->getTranslation(Yii::$app->globalData->lang)->quote ?>»։</p>
                <?php endif; ?>
                <div class="body-copy">
                    <?= $content?->getTranslation(Yii::$app->globalData->lang)->text ?>
                </div>
            </div>
        </div>
    </section>
<?php else: ?>

    <section class="inner-section">
        <div class="wrap inner-grid-2">
            <div>
                <?php if ($content?->getTranslation(Yii::$app->globalData->lang)->pre_title): ?>
                    <div class="eyebrow red"><?= $content?->getTranslation(Yii::$app->globalData->lang)->pre_title ?></div>
                <?php endif; ?>

                <h2 class="display-inner"><?= $content?->getTranslation(Yii::$app->globalData->lang)->title ?></h2>

                <div class="body-copy">
                    <?= $content?->getTranslation(Yii::$app->globalData->lang)->text ?>
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

<?php endif; ?>
