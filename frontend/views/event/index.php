<?php

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use common\components\DateHelper;
use common\models\TranslationModel;
use yii\web\View;


$dates = [
        '2026-11-18' => [
                'color' => '#7f1424',
                'textColor' => '#ffffff',
                'url' => Url::to(['/event/view', 'id' => 1]),
        ],
        '2026-11-20' => [
                'color' => '#7f1424',
                'textColor' => '#ffffff',
                'url' => Url::to(['/event/view', 'id' => 2]),
        ],
];

$datesJson = Json::htmlEncode($dates);

?>


    <section class="inner-section" id="calendar">
        <div class="wrap event-calendar">
            <div class="calendar-box">
                <h3 style="font-family:Georgia,serif;font-size:28px">Օրացույց</h3>
                <div class="calendar-container">
                    <?= Html::tag('div', '', ['id' => 'calendar-box']) ?>
                </div>
            </div>

            <div id="upcoming">
                <div class="eyebrow red">Առաջիկա</div>
                <h2 class="display-inner">Միջոցառումներ</h2>


                <?php
                if (isset($events) && !empty($events)) : ?>
                    <?php foreach ($events as $event) : ?>
                        <?php
                        $url = '/' . Yii::$app->globalData->lang . '/events/view/' . $event->id;
                        ?>
                        <a class="list-row" href="<?= $url ?>">

                            <div class="date">

                                <?= date('d', strtotime($event->date)) ?>
                                <?= DateHelper::getMonthsShort($event->date, Yii::$app->globalData->lang) ?>
                            </div>
                            <div>
                                <h3>
                                    <?= TranslationModel::getTranslation($event, Yii::$app->globalData->lang) ?>
                                </h3>
                                <p>
                                    <?= TranslationModel::getTranslation($event, Yii::$app->globalData->lang, 'place') ?>
                                </p>
                            </div>

                            <span class="arrow">→</span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

        </div>
    </section>

    <section class="rich-photo-section">
        <div class="wrap">
            <div class="eyebrow red">Առաջիկա միջոցառումներ</div>
            <h2 class="display-inner">Համալսարանը շարժման մեջ</h2>
            <div class="event-cards-rich">
                <article class="event-photo-card"><img src="/images/editorial/graduation-balloon.jpg"
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
                <article class="event-photo-card"><img src="/images/editorial/campus-night.jpg"
                                                       alt="Երեկոյան միջոցառում">
                    <div class="event-over"><span class="event-date-badge">02 ՀՈԿ</span>
                        <h3>Համալսարանական երեկո</h3>
                        <p>ՀՊՏՀ · 19:00</p></div>
                </article>
            </div>
        </div>
    </section>

<?php
$this->registerJsFile(
        'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js',
        ['position' => View::POS_END]
);

$this->registerJsFile(
        '@web/js/calendar.js',
        [
                'depends' => ['yii\web\JqueryAsset'],
                'position' => View::POS_END,
        ]
);

$dateTmp = $date ?? date('Y-m-d');

$year = date('Y');
$month = date('m');

if ($dateTmp) {
    $year = date('Y', strtotime($dateTmp));
    $month = date('m', strtotime($dateTmp));
}

$this->registerJs(<<<JS
    getCalendarData($year,$month)
JS, \yii\web\View::POS_READY);


$this->registerJs(<<<JS
    caledarInitialDate = "$dateTmp";
JS, \yii\web\View::POS_HEAD);
?>