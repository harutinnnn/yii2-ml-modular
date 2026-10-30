<?php

use common\components\I18n;
use common\components\NumberHelper;
use common\models\TranslationModel;

?>

<section class="inner-section">
    <div class="wrap inner-grid-2">
        <div>
            <div class="eyebrow red"><?= I18n::translate('asue') ?></div>
            <h2 class="display-inner">
                <?= I18n::translate('about_the_department') ?>
            </h2>
            <div class="body-copy">
                <div id="about">
                    <?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang, 'about_chair') ?>
                </div>
            </div>
        </div>
        <aside data-quick-links="">
            <div class="meta-label">Արագ հղումներ</div>
            <div class="link-list">
                <a href="education.html">
                    <span>
                        <small>Տես նաև</small>
                        <br>Կրթական ծրագրեր</span>
                </a>
                <a href="structure.html">
                    <span>
                        <small>ՀՊՏՀ կառուցվածք</small>
                        <br>Ֆակուլտետներ և ամբիոններ</span>
                </a>
                <a href="contact.html">
                    <span>
                        <small>Կապ</small>
                        <br>Գրել համալսարանին</span>
                </a>
            </div>
        </aside>
    </div>
</section>

<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red">Բաժիններ</div>
        <h2 class="display-inner">Կրթական ծրագրեր</h2>
        <div class="cards">
            <?php if (isset($educationalPrograms) && !empty($educationalPrograms)): ?>
                <?php foreach ($educationalPrograms as $i => $educationalProgram): ?>

                    <article class="card">
                        <div class="card-number"><?= NumberHelper::numberZeroFill($i + 1) ?></div>
                        <h3>
                            <?= TranslationModel::getTranslation($educationalProgram->educationProgramm, Yii::$app->globalData->lang) ?>
                        </h3>
                        <p>
                            <?= TranslationModel::getTranslation($educationalProgram->educationProgramm, Yii::$app->globalData->lang, 'desc') ?>
                        </p>
                        <a href="/<?= Yii::$app->globalData->lang ?>/education/education/program/<?= $educationalProgram->educationProgramm->id ?>"><?= I18n::translate('open') ?> →</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="inner-section compact white" id="staff">
    <div class="wrap">
        <div class="department-head">
            <div class="meta-label">Ամբիոնի ղեկավարություն</div>
            <h2>Ամբիոնի վարիչ</h2>
            <p>Պրոֆեսոր · կառավարման գիտություններ</p><a class="btn-outline" href="teacher-profile.html">Դիտել
                պրոֆիլը →</a></div>
        <h2 class="display-inner">Դասախոսական կազմ</h2>
        <ul class="staff-list" data-staff-list="">
            <li><a href="teacher-profile.html"><strong>Աննա Հարությունյան</strong><span>Պրոֆեսոր · Ռազմավարական կառավարում</span><span
                            aria-hidden="true">→</span></a></li>
            <li><a href="teacher-profile-2.html"><strong>Դավիթ
                        Սարգսյան</strong><span>Դոցենտ · Ֆինանսական կառավարում</span><span
                            aria-hidden="true">→</span></a>
            </li>
            <li><a href="teacher-profile-3.html"><strong>Մարիամ Գրիգորյան</strong><span>Դասախոս · Մարքեթինգ և հաղորդակցություն</span><span
                            aria-hidden="true">→</span></a></li>
            <li><a href="teacher-profile-4.html"><strong>Արամ Պետրոսյան</strong><span>Պրոֆեսոր · Տնտեսական քաղաքականություն</span><span
                            aria-hidden="true">→</span></a></li>
            <li><a href="teacher-profile-5.html"><strong>Լիլիթ
                        Ավետիսյան</strong><span>Դոցենտ · Տվյալների վերլուծություն</span><span
                            aria-hidden="true">→</span></a></li>
            <li><a href="teacher-profile-6.html"><strong>Գոռ
                        Մանուկյան</strong><span>Դասախոս · Ձեռնարկատիրություն</span><span aria-hidden="true">→</span></a>
            </li>
        </ul>
    </div>
</section>