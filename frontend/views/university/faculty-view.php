<?php

use common\components\I18n;
use common\components\NumberHelper;
use common\models\TranslationModel;

?>
<section class="inner-section">
    <div class="wrap inner-grid-2">
        <div>
            <div class="eyebrow red"><?= I18n::translate('asue') ?></div>
            <h2 class="display-inner"><?= I18n::translate('about_the_faculty') ?></h2>
            <div class="body-copy">
                <div id="about">

                    <?php if (isset($faculty)): ?>
                        <?= TranslationModel::getTranslation($faculty, Yii::$app->globalData->lang, 'text') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php

        if (isset($sideMenuList) && !empty($sideMenuList)): ?>
            <aside>
                <div class="meta-label"><?= I18n::translate('quick_links') ?></div>
                <div class="link-list">
                    <?php foreach ($sideMenuList as $sideMenu): ?>

                        <a href="<?= $sideMenu['url'] ?>">
                            <span><?= $sideMenu['title'] ?></span>
                        </a>

                    <?php endforeach; ?>
                </div>
            </aside>
        <?php endif; ?>
    </div>

</section>

<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('sections') ?></div>
        <h2 class="display-inner">Կրթական ծրագրեր</h2>
        <div class="cards">
            <article class="card">
                <div class="card-number">01</div>
                <h3>Կառավարում</h3>
                <p>Բակալավրի կրթական ծրագիր՝ կազմակերպությունների և նախագծերի կառավարման հիմքերով։</p>
                <a href="education.html#programs">Բացել →</a>
            </article>
            <article class="card">
                <div class="card-number">02</div>
                <h3>Բիզնես վարչարարություն</h3>
                <p>Մագիստրոսական ծրագիր՝ ռազմավարության, ֆինանսների և առաջնորդության շեշտադրումներով։</p>
                <a href="education.html#programs">Բացել →</a>
            </article>
            <article class="card">
                <div class="card-number">03</div>
                <h3>Ձեռնարկատիրություն</h3>
                <p>Կիրառական ծրագիր՝ նորարարության, բիզնես մոդելների և շուկայի զարգացման համար։</p>
                <a href="education.html#programs">Բացել →</a>
            </article>
        </div>
    </div>
</section>
<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('sections') ?></div>
        <h2 class="display-inner"><?= I18n::translate('departments') ?></h2>
        <div class="cards">
            <?php if (isset($chairs) && !empty($chairs)): ?>
                <?php foreach ($chairs as $i => $chair): ?>

                    <article class="card">
                        <div class="card-number"><?= NumberHelper::numberZeroFill($i + 1) ?></div>
                        <h3>
                            <?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang) ?>
                        </h3>
                        <p>
                            <?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang, 'text') ?>
                        </p>
                        <?php

                        $link = '/' . Yii::$app->globalData->lang . '/about-the-university/chair/' . $chair->id;

                        ?>
                        <a href="<?= $link ?>"><?= I18n::translate('open') ?> →</a>
                    </article>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>