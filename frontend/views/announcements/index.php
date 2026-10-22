<section class="inner-section">
    <div class="wrap">

        <?php use common\components\DateHelper;
        use common\components\I18n;
        use common\models\TranslationModel;
        use yii\widgets\LinkPager;

        if (isset($announcements) && !empty($announcements)): ?>
            <?php foreach ($announcements as $announcement): ?>

                <a href="/<?= Yii::$app->globalData->lang ?>/announcements/<?= TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang, 'slug') ?>">
                    <div class="list-row">

                        <div class="date"><?= date('d', strtotime($announcement->date)) ?> <?= DateHelper::getMonthsShort($announcement->date, Yii::$app->globalData->lang) ?></div>
                        <div>
                            <h3><?= TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang) ?></h3>

                            <p><?= TranslationModel::getTranslation($announcement->category, Yii::$app->globalData->lang) ?></p>
                        </div>
                        <span class="arrow">→</span>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php
        echo LinkPager::widget([
                'pagination' => $pagination,
                'prevPageLabel' => I18n::translate('previous'),
                'nextPageLabel' => I18n::translate('next'),
                'maxButtonCount' => 5,
        ]);
        ?>
    </div>

</section>
<section class="rich-photo-section">
    <div class="wrap">

        <!--TODO verjin temaner?-->
        <div class="eyebrow red">Պատմություններ համալսարանից</div>
        <h2 class="display-inner">Վերջին թեմաները՝ լուսանկարներով</h2>
        <div class="story-grid">
            <?php if (isset($latestNews) && !empty($latestNews)): ?>
                <?php foreach ($latestNews as $newsItem): ?>
                    <article class="story-card">
                        <div class="story-img">
                            <img src="<?= $newsItem->image ?>"
                                 alt="<?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang) ?>"/>
                        </div>
                        <div class="story-copy">
                            <small class="meta-label">
                                <?= TranslationModel::getTranslation($newsItem->category, Yii::$app->globalData->lang) ?>
                            </small>
                            <h3><?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang) ?></h3>
                            <p>
                                <?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang, 'short') ?>
                            </p>
                            <a href="/<?= Yii::$app->globalData->lang ?>/news/<?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang, 'slug') ?>"><?= I18n::translate('read_more') ?>
                                →</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>