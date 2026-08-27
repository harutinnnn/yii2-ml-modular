<?php

use common\components\I18n;

?>
<section class="stats-red">
    <div><b><?= $facultiesCount ?? '-' ?></b><span><?= I18n::translate('faculty') ?></span></div>
    <div><b><?= $chairCount ?? '-' ?></b><span><?= I18n::translate('chair_and_professional_direction') ?></span></div>
    <div><b><?= Yii::$app->globalData->settings['educational_program'] ?></b><span><?= I18n::translate('educational_program') ?></span></div>
    <div><b><?= Yii::$app->globalData->settings['student_and_researcher'] ?></b><span><?= I18n::translate('student_and_researcher') ?></span></div>
</section>

<section class="section o1-intro" id="about">
    <div class="wrap o1-intro-layout reveal">
        <div>
            <div class="kicker" style="color:var(--red)"><?= I18n::translate('asue_today') ?></div>
            <h2 class="big-quote">Տնտեսագիտական կրթության կենտրոն՝ բաց գաղափարների, նոր գիտելիքի և պատասխանատու
                առաջնորդության համար։</h2></div>
        <div class="o1-intro-copy"><p>ՀՊՏՀ-ն միավորում է կրթությունը, գիտությունը և բիզնես միջավայրի հետ գործնական
                կապը՝ ձևավորելով մասնագետներ, որոնք կարողանում են հասկանալ փոփոխվող տնտեսությունը և ստեղծել արժեք։</p>
            <p>Մեր համալսարանական փորձը կառուցված է ընտրության շուրջ՝ մասնագիտական ծրագրեր, հետազոտական
                նախաձեռնություններ, միջազգային շարժունություն, կարիերայի զարգացում և ակտիվ ուսանողական կյանք։</p><a
                    class="link-arrow" href="about.html">Բացահայտել ՀՊՏՀ-ն</a></div>
    </div>
</section>
<section class="section" id="education">
    <div class="wrap">
        <div class="kicker" style="color:var(--red)"><?= I18n::translate('education') ?></div>
        <h2 class="display">Կառուցեք ձեր մասնագիտական ճանապարհը</h2>
        <p class="lead">Ընտրեք կրթական մակարդակը և ուղղությունը՝ տնտեսագիտությունից ու ֆինանսներից մինչև կառավարում,
            մարքեթինգ, հաշվապահություն և թվային տնտեսություն։</p>
        <div class="grid-4" style="margin-top:46px">
            <article class="edu-card reveal">
                <div class="num">01</div>
                <h3>Բակալավրիատ</h3>
                <p>Հիմնարար գիտելիք, կիրառական հմտություններ և մասնագիտական կողմնորոշում առաջին իսկ կուրսից։</p><a
                        class="link-arrow" href="bachelor.html">Տեսնել ծրագրերը</a></article>
            <article class="edu-card reveal">
                <div class="num">02</div>
                <h3>Մագիստրատուրա</h3>
                <p>Խորացված մասնագիտացում, հետազոտական աշխատանք և կապ ոլորտային փորձագետների հետ։</p><a
                        class="link-arrow" href="bachelor.html">Տեսնել ծրագրերը</a></article>
            <article class="edu-card reveal">
                <div class="num">03</div>
                <h3>Ասպիրանտուրա</h3>
                <p>Գիտական հետազոտություն և ակադեմիական կարիերայի զարգացում տնտեսագիտական ուղղություններով։</p><a
                        class="link-arrow" href="phd.html">Գիտական ուղի</a></article>
            <article class="edu-card reveal">
                <div class="num">04</div>
                <h3>Շարունակական կրթություն</h3>
                <p>Կարճաժամկետ ծրագրեր և մասնագիտական վերապատրաստում փոփոխվող աշխատաշուկայի համար։</p><a
                        class="link-arrow" href="short-courses.html">Իմանալ ավելին</a></article>
        </div>
    </div>
</section>
<section class="o1-feature" id="research">
    <div aria-label="Ուսանողական քննարկում" class="o1-feature-image" role="img"></div>
    <div class="o1-feature-copy reveal">
        <div class="kicker">Գիտություն և հետազոտություն</div>
        <h2 class="display">Գաղափարներից՝ կիրառելի լուծումների</h2>
        <p>ՀՊՏՀ-ում հետազոտությունը կենտրոնանում է Հայաստանի տնտեսության, ֆինանսական համակարգերի, կառավարման,
            հանրային քաղաքականության և տարածաշրջանային զարգացման արդիական խնդիրների վրա։ Ուսանողները ներգրավվում են
            գիտական նախագծերում և աշխատում դասախոսների ու ոլորտային գործընկերների հետ։</p>
        <div style="margin-top:28px"><a class="btn ghost-light" href="research.html">Հետազոտական նորություններ</a>
        </div>
    </div>
</section>
<section class="section" id="news">
    <div class="wrap">
        <div style="display:flex;justify-content:space-between;align-items:end;gap:30px">
            <div>
                <div class="kicker" style="color:var(--red)">Նորություններ</div>
                <h2 class="display">ՀՊՏՀ համայնքի վերջին պատմությունները</h2></div>
            <a class="link-arrow" href="news.html">Բոլոր նորությունները</a></div>
        <div class="grid-3" style="margin-top:38px">
            <article class="news-card reveal"><img alt="Շրջանավարտներ"
                                                   src="/images/home/graduates-close.jpg"/><small>ՀԱՄԱՅՆՔ ·
                    08.08.2026</small>
                <h3>Նոր շրջանավարտներ՝ նոր հնարավորությունների ճանապարհին</h3>
                <p>Ամփոփում ենք ավարտական շրջանի կարևոր պահերը և մեր շրջանավարտների ձեռքբերումները։</p><a
                        class="link-arrow" href="news-detail.html">Կարդալ</a></article>
            <article class="news-card reveal"><img alt="Ուսանողական քննարկում"
                                                   src="/images/home/student-discussion.jpg"/><small>ԿՐԹՈՒԹՅՈՒՆ ·
                    05.08.2026</small>
                <h3>Նոր ուսումնական տարին՝ ծրագրային նոր շեշտադրումներով</h3>
                <p>Գործնական նախագծերը և ոլորտային համագործակցությունը ավելի մեծ տեղ են զբաղեցնելու ծրագրերում։</p>
                <a class="link-arrow" href="news-detail.html">Կարդալ</a></article>
            <article class="news-card reveal"><img alt="ՀՊՏՀ միջոցառում" src="/images/home/asue-balloon.jpg"/><small>ՀԱՄԱԼՍԱՐԱՆ
                    · 01.08.2026</small>
                <h3>ՀՊՏՀ-ն նշում է համալսարանական կարևոր տարեդարձը</h3>
                <p>Միջոցառումներ, հանդիպումներ և պատմություններ, որոնք միավորում են սերունդներին։</p><a
                        class="link-arrow" href="news-detail.html">Կարդալ</a></article>
        </div>
    </div>
</section>
<section class="section events-strip" id="events">
    <div class="wrap">
        <div class="kicker" style="color:#d4a1a6">Օրացույց</div>
        <h2 class="display" style="color:#fff">Առաջիկա միջոցառումներ</h2>
        <div style="margin-top:35px">
            <article class="event-row">
                <div class="event-date"><b>04</b><span>ՍԵՊՏԵՄԲԵՐ</span></div>
                <h3>Բաց դռների օր ապագա դիմորդների համար</h3>
                <div class="place">Գլխավոր մասնաշենք · 14:00</div>
            </article>
            <article class="event-row">
                <div class="event-date"><b>12</b><span>ՍԵՊՏԵՄԲԵՐ</span></div>
                <h3>Տնտեսական զարգացման նոր միտումներ. բաց դասախոսություն</h3>
                <div class="place">Գիտաժողովների դահլիճ · 16:30</div>
            </article>
            <article class="event-row">
                <div class="event-date"><b>20</b><span>ՍԵՊՏԵՄԲԵՐ</span></div>
                <h3>Միջազգային շարժունության ծրագրերի տեղեկատվական հանդիպում</h3>
                <div class="place">Միջազգային կենտրոն · 13:00</div>
            </article>
        </div>
    </div>
</section>
<section class="section" id="international">
    <div class="wrap">
        <div class="o1-intro-layout">
            <div>
                <div class="kicker" style="color:var(--red)">Միջազգային ՀՊՏՀ</div>
                <h2 class="display">Սովորել Երևանում, մտածել գլոբալ</h2></div>
            <div class="o1-intro-copy"><p>Միջազգային գործընկեր համալսարանների հետ ուսանողական և դասախոսական
                    շարժունությունը, համատեղ ծրագրերն ու գիտական կապերը հնարավորություն են տալիս ՀՊՏՀ համայնքին աշխատել
                    միջազգային միջավայրում։</p>
                <div class="grid-3" style="margin-top:28px">
                    <div class="edu-card">
                        <div class="num">01</div>
                        <h3 style="font-size:22px">Փոխանակման ծրագրեր</h3></div>
                    <div class="edu-card">
                        <div class="num">02</div>
                        <h3 style="font-size:22px">Միջազգային նախագծեր</h3></div>
                    <div class="edu-card">
                        <div class="num">03</div>
                        <h3 style="font-size:22px">Հյուր դասախոսներ</h3></div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section o1-campus" id="campus">
    <div class="wrap">
        <div class="kicker" style="color:var(--red)">Ուսանողական կյանք</div>
        <h2 class="display">Համալսարան՝ նաև լսարանից դուրս</h2>
        <p class="lead">Ակտիվ համայնք, ակումբներ, միջոցառումներ, կարիերայի հնարավորություններ և միջավայր, որտեղ
            ձևավորվում են մասնագիտական ու մարդկային կապեր։</p>
        <div class="campus-grid" style="margin-top:42px">
            <div class="large"><img alt="ՀՊՏՀ շրջանավարտներ" src="/images/home/graduates-wide.jpg"/>
                <div class="caption">Շրջանավարտների օրը ՀՊՏՀ բակում</div>
            </div>
            <div class="small">
                <div><img alt="ՀՊՏՀ գիշերային տեսարան" src="/images/home/campus-night.jpg"/>
                    <div class="caption">Համալսարանական մասնաշենք</div>
                </div>
                <div><img alt="ՀՊՏՀ մուտք" src="/images/home/campus-entrance.jpg"/>
                    <div class="caption">ՀՊՏՀ համալսարանական միջավայր</div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cta-wide" id="admission">
    <div class="wrap cta-layout"><h2>Պատրա՞ստ եք սկսել ձեր հաջորդ քայլը ՀՊՏՀ-ում։</h2><a class="btn light"
                                                                                         href="admission.html">Ընդունելություն
            2026 ↗</a></div>
</section>