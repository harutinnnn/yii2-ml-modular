<?php


use common\components\I18n; ?>
<section class="o1-hero">
    <div class="o1-hero-copy">
        <div class="kicker"><?= I18n::translate('home_page_header_pre_title') ?></div>
        <h1><?= I18n::translate('home_page_header_title') ?></h1>
        <p>
            <?= I18n::translate('home_page_header_after_title') ?>
        </p>
        <div class="o1-actions">
            <a class="btn light" href="admission.html">
                <?= I18n::translate('home_page_admission_link_text') ?>
            </a>
            <a class="btn ghost-light" href="education.html">
                <?= I18n::translate('home_page_educational_link_text') ?>
            </a>
        </div>
    </div>
    <div class="o1-rail">
        <a href="/<?= Yii::$app->globalData->lang ?>/news"><?= I18n::translate('home_page_header_latest_news') ?> <span>↗</span></a>
        <a href="/<?= Yii::$app->globalData->lang ?>/events"><?= I18n::translate('home_page_header_upcomming_events') ?> <span>↗</span></a>
        <a href="/<?= Yii::$app->globalData->lang ?>/student-life"><?= I18n::translate('home_page_header_student_opportunities') ?> <span>↗</span></a>
    </div>
</section>