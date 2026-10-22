<?php
$content = $this->context->pageData['content'];

?>
<nav class="inner-subnav">
    <div class="wrap">
        <a href="program-detail.html">Ծրագրի մասին</a>
        <a href="#curriculum">Ուսումնական պլան</a>
        <a href="program-detail.html">Ընդունելություն</a>
        <a href="program-detail.html">Կարիերա</a>
    </div>
</nav>

<section class="inner-section">
    <div class="wrap">
        <div class="program-hero-box">
            <div class="eyebrow red"><?= $level->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?></div>
            <h2 class="display-inner"><?= $program->getTranslation(Yii::$app->globalData->lang)->title ?></h2>
            <div class="body-copy">
                <?= $program->getTranslation(Yii::$app->globalData->lang)->desc ?>
            </div>
            <div class="program-meta">
                <div><span class="meta-label">Տևողություն</span><b><?= $program->duration_by_year ?> տարի</b></div>
                <div><span class="meta-label">Որակավորում</span><b>Bachelor</b></div>
                <div><span class="meta-label">Ուսուցում</span><b>Առկա</b></div>
                <div>
                    <span class="meta-label">Լեզու</span><b><?= strtoupper(implode(' / ', explode(',', $program->languages))) ?></b>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (isset($content) && !empty($content)): ?>
    <section class="rich-photo-section">
        <div class="wrap">
            <div class="photo-feature-split">
                <div class="visual"><img src="<?= $content->image ?? null ?>" alt="ՀՊՏՀ ուսանողներ"></div>
                <div class="photo-feature-copy">
                    <div class="eyebrow red">Կրթական միջավայր</div>
                    <h2><?= $content->getTranslation(Yii::$app->globalData->lang)->title ?></h2>
                    <?= $content->getTranslation(Yii::$app->globalData->lang)->text ?>
                    <a class="btn-red" href="education.html">Դիտել կրթական ծրագրերը →</a></div>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="inner-section white" id="curriculum">
    <div class="wrap inner-grid-2">
        <div>
            <div class="eyebrow red">Ուսումնական պլան</div>
            <h2 class="display-inner">Ինչ եք ուսումնասիրելու</h2>
            <div class="accordion">
                <?php if (isset($plans) && !empty($plans)): ?>
                    <?php foreach ($plans as $i => $plan): ?>
                        <details <?= !$i ? 'open' : '' ?>>
                            <summary><?= $plan->getTranslation(Yii::$app->globalData->lang)->title ?></summary>
                            <div class="answer">
                                <?= $plan->getTranslation(Yii::$app->globalData->lang)->text ?>
                            </div>
                        </details>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>
        <aside>
            <div class="meta-label"><?= \common\components\I18n::translate('application') ?></div>
            <h3 style="font-family:Georgia,serif;font-size:30px">
                <?= \common\components\I18n::translate('ready_to_apply') ?>
            </h3>
            <p class="body-copy">
                <?= \common\components\I18n::translate('ready_to_apply_text') ?>
            </p>
            <a class="btn-red"
               href="/<?= Yii::$app->globalData->lang ?>/admission/online-application"><?= \common\components\I18n::translate('admission') ?>
                →</a>
        </aside>
    </div>
</section>