<?php

use common\components\DateHelper;
use common\components\I18n;
use common\helpers\EditorJsHelper;
use common\models\Language;
use common\models\TranslationModel;
use yii\helpers\ArrayHelper;

$this->params['hideMainHeader'] = true;
$this->params['customHeader'] = '//partial/dynamic-header';
$languages = ArrayHelper::map(Language::find()->all(), 'code', 'name');
?>
<section class="inner-section">
    <div class="wrap event-detail-grid">
        <?php if ($eventItem): ?>

            <article>
                <div class="eyebrow red"><?= TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang, 'topic') ?></div>
                <h2 class="display-inner"><?= TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang) ?></h2>
                <div class="body-copy">
                    <?= EditorJsHelper::render(TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang, 'text')) ?>
                </div>

            </article>
            <aside>
                <div class="event-info">
                    <div>
                        <strong>Ամսաթիվ</strong>
                        <?= date('d', strtotime($eventItem->date)) ?>
                        <?= DateHelper::getMonth($eventItem->date, Yii::$app->globalData->lang) ?>,
                        <?= date('Y', strtotime($eventItem->date)) ?>
                    </div>
                    <div>
                        <strong>Ժամ</strong>
                        <?= $eventItem->time ?>
                    </div>
                    <div>
                        <strong><?= I18n::translate('location') ?></strong>
                        <?= TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang, 'place') ?>
                    </div>
                    <div>
                        <strong><?= I18n::translate('organizer') ?></strong>
                        <?= TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang, 'organiser') ?>
                    </div>
                    <div>
                        <strong><?= I18n::translate('contact') ?></strong>
                        <?= TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang, 'contact') ?>
                    </div>
                    <a href="/<?= Yii::$app->globalData->lang ?>/events/registration/<?= $eventItem->id ?>">
                        <button class="btn-red" style="width:100%;margin-top:20px">
                            <?= I18n::translate('register_to_participate') ?>
                        </button>
                    </a>
                </div>
            </aside>
        <?php else: ?>

            <div>
                <h2 class="display-inner">
                    <?= I18n::translate('no_content_with_current_language_text') ?>
                </h2>
                <div class="body-copy">
                    <?= I18n::translate('for_example') ?>`

                    <?php if (isset($navLangList) && !empty($navLangList)): ?>
                        <?php foreach ($navLangList as $langKey => $newsSlug): ?>
                            <?php if ($langKey !== Yii::$app->globalData->lang): ?>

                                <a href="<?= $newsSlug ?>"
                                   class="no-content-lang-url"><?= $languages[$langKey] ?>
                                </a>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>

<section class="rich-photo-section">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('upcoming_events') ?></div>
        <h2 class="display-inner"><?= I18n::translate('university_in_motion') ?></h2>
        <div class="event-cards-rich">
            <article class="event-photo-card">
                <img src="/images/editorial/graduation-balloon.jpg"
                     alt="ՀՊՏՀ միջոցառում">
                <div class="event-over"><span class="event-date-badge">18 ՍԵՊ</span>
                    <h3>Բաց համալսարանական օր</h3>
                    <p>Գլխավոր մասնաշենք · 11:00</p></div>
            </article>
            <article class="event-photo-card"><img src="/images/editorial/student-discussion.jpg" alt="Սեմինար">
                <div class="event-over"><span class="event-date-badge">24 ՍԵՊ</span>
                    <h3>Տնտեսագիտական բաց սեմինար</h3>
                    <p>Գիտական կենտրոն · 15:30</p></div>
            </article>
            <article class="event-photo-card"><img src="/images/images/editorial/campus-night.jpg"
                                                   alt="Երեկոյան միջոցառում">
                <div class="event-over"><span class="event-date-badge">02 ՀՈԿ</span>
                    <h3>Համալսարանական երեկո</h3>
                    <p>ՀՊՏՀ · 19:00</p></div>
            </article>
        </div>
    </div>
</section>