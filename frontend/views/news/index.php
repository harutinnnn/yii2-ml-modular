<?php

use common\components\DateHelper;
use common\components\I18n;
use common\models\TranslationModel;
use yii\widgets\LinkPager;

$this->params['hideMainHeader'] = true;
$this->params['customHeader'] = '//news/header';

?>

<section class="inner-section" id="latest">
    <div class="wrap">
        <div class="listing-toolbar">
            <div class="filter-group">
                <a href="/<?= Yii::$app->globalData->lang ?>/news">
                    <button class="filter-pill active">Բոլորը</button>
                </a>
                <?php if (isset($newsCategories) && !empty($newsCategories)): ?>
                    <?php foreach ($newsCategories as $newsCategory): ?>
                        <a href="/<?= Yii::$app->globalData->lang ?>/news?category=<?= $newsCategory->id ?>">

                            <button class="filter-pill">
                                <?= TranslationModel::getTranslation($newsCategory, Yii::$app->globalData->lang) ?>
                            </button>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <input class="search-mini" placeholder="Որոնել նորություններ">
        </div>

        <div class="story-grid">
            <?php if (isset($news) && !empty($news)): ?>
                <?php foreach ($news as $newsItem): ?>

                    <article class="story-card">
                        <div class="story-img">
                            <img src="<?= $newsItem->image ?>"
                                 alt="<?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang) ?>"/>
                        </div>
                        <div class="story-copy">
                            <small class="meta-label">
                                <?= date('d', strtotime($newsItem->image)) ?>
                                <?= DateHelper::getMonthsShort($newsItem->date, Yii::$app->globalData->lang) ?> ·
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

        <?php
        echo LinkPager::widget([
                'pagination' => $pagination,
                'prevPageLabel' => I18n::translate('previous'),
                'nextPageLabel' => I18n::translate('next'),
                'maxButtonCount' => 5,
        ]);
        ?>

        <div style="margin-top:34px">
            <?php foreach ($announcements as $announcement): ?>
                <a class="list-row"
                   href="/<?= Yii::$app->globalData->lang ?>/announcements/<?= TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang, 'slug') ?>">
                    <div class="date"><?= date('d', strtotime($announcement->date)) ?> <?= DateHelper::getMonthsShort($announcement->date, Yii::$app->globalData->lang) ?></div>
                    <div>
                        <h3><?= TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang) ?></h3>
                        <p><?= TranslationModel::getTranslation($announcement->category, Yii::$app->globalData->lang) ?></p>
                    </div>
                    <span class="arrow">→</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (isset($latestNews) && !empty($latestNews)): ?>
    <section class="rich-photo-section">
        <div class="wrap">
            <div class="eyebrow red">Պատմություններ համալսարանից</div>
            <h2 class="display-inner">Վերջին թեմաները՝ լուսանկարներով</h2>
            <div class="story-grid">
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
            </div>
        </div>
    </section>
<?php endif; ?>
