<?php

use common\helpers\EditorJsHelper;
use common\models\TranslationModel;

$this->params['hideMainHeader'] = true;
$this->params['customHeader'] = '//announcements/header';
?>

<section class="inner-section">
    <div class="wrap inner-grid-2">
        <article>
            <div class="meta-label">
                <?= date('d', strtotime($newsItem->date)) ?>
                <?= \common\components\DateHelper::getMonth($newsItem->date, \Yii::$app->globalData->lang) ?>,
                <?= date('Y', strtotime($newsItem->date)) ?> · <?= TranslationModel::getTranslation($newsItem->category, Yii::$app->globalData->lang) ?>
            </div>

            <h2 class="display-inner">
                <?= TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang) ?>
            </h2>
            <div class="body-copy">
                <?= EditorJsHelper::render(TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang,'text')) ?>
            </div>
        </article>
        <aside>
            <div class="meta-label">Կիսվել / թեմաներ</div>
            <div class="link-list"><a href="international.html">Միջազգային համագործակցություն</a><a
                        href="education.html">Կրթություն</a><a href="news.html">Բոլոր նորությունները</a></div>
        </aside>
    </div>
</section>
<section class="rich-photo-section">
    <div class="wrap">
        <div class="eyebrow red">Պատմություններ համալսարանից</div>
        <h2 class="display-inner">Վերջին թեմաները՝ լուսանկարներով</h2>
        <div class="story-grid">
            <article class="story-card">
                <div class="story-img"><img src="/images/editorial/graduates-group.jpg" alt="Շրջանավարտներ"></div>
                <div class="story-copy"><small class="meta-label">Ուսանողներ</small>
                    <h3>Ավարտական օր՝ նոր ճանապարհի սկիզբ</h3>
                    <p>ՀՊՏՀ համայնքը նշում է ուսանողների հերթական կարևոր հանգրվանը։</p><a href="news-detail.html">Կարդալ
                        →</a></div>
            </article>
            <article class="story-card">
                <div class="story-img"><img src="/images/editorial/aerial-balloon.jpg" alt="ՀՊՏՀ միջոցառում"></div>
                <div class="story-copy"><small class="meta-label">Համալսարան</small>
                    <h3>ՀՊՏՀ-ն քաղաքային մշակութային օրակարգում</h3>
                    <p>Համալսարանական նախաձեռնությունները դուրս են գալիս լսարանից և միավորում համայնքը։</p><a
                            href="news-detail.html">Կարդալ →</a></div>
            </article>
            <article class="story-card">
                <div class="story-img"><img src="/images/editorial/student-discussion.jpg" alt="Քննարկում"></div>
                <div class="story-copy"><small class="meta-label">Կրթություն</small>
                    <h3>Բաց քննարկումներ և նոր ուսումնական ձևաչափեր</h3>
                    <p>Ուսանողները և դասախոսները քննարկում են արդի տնտեսական թեմաները։</p><a
                            href="news-detail.html">Կարդալ →</a></div>
            </article>
        </div>
    </div>
</section>