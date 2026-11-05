<?php

use common\models\TranslationModel;

$this->params['customHeader'] = '//partial/dynamic-header';
$nameInitials =
        substr(trim($additional->first_name ?? ''), 0, 1) .
        substr(trim($additional->last_name ?? ''), 0, 1);
$name = ($additional->first_name ?? '') . ' ' . ($additional->last_name ?? '');
$teacher_academic_degree = $additional->teacher_academic_degree ?? '';

?>

<main>

    <nav class="inner-subnav profile-tabs" aria-label="Դասախոսի տվյալներ">
        <div class="wrap" role="tablist">
            <button id="tab-bio" role="tab" aria-controls="bio-panel" aria-selected="true" tabindex="0">
                Հիմնական տեղեկատվություն
            </button>
            <button id="tab-research" role="tab" aria-controls="research-panel" aria-selected="false" tabindex="-1">
                Գիտական գործունեություն
            </button>
        </div>
    </nav>
    <div id="bio-panel" role="tabpanel" aria-labelledby="tab-bio">
        <section class="inner-section">
            <div class="wrap profile-hero">
                <div aria-label="Դասախոսի լուսանկարի placeholder" class="profile-placeholder">
                    <div class="initials"><?= $nameInitials ?></div>
                    <div class="credential"><?= $teacher_academic_degree ?> · Տ.գ.դ.</div>
                    <div class="placeholder-name"><?= $name ?></div>
                </div>
                <div>
                    <div class="eyebrow red"><?= $teacher_academic_degree ?></div>
                    <h2 class="display-inner"><?= $name ?></h2>
                    <div class="profile-meta">
                        <span class="tag"><?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang) ?></span>
                        <span class="tag">Տ.գ.դ.</span>
                        <span class="tag">Ռազմավարական կառավարում</span>
                    </div>
                    <div class="body-copy" id="bio">
                        <p>Ակադեմիական և մասնագիտական գործունեությունը կենտրոնացած է
                            ռազմավարական կառավարման, կազմակերպական զարգացման և նորարարության վրա։</p>
                    </div>
                    <div class="facts-list">
                        <div>
                            <strong>Էլ. փոստ</strong>
                            <span><?= $user->contact_email ?></span>
                        </div>
                        <div>
                            <strong>Խորհրդատվություն</strong>
                            <span>Երեքշաբթի 15:00–17:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="inner-section compact" id="">
            <div class="wrap"><h2 class="display-inner">Մասնագիտական տվյալներ</h2>
                <dl class="cv-details" data-public-cv>
                    <div>
                        <dt>Կրթություն</dt>
                        <dd>Տնտեսագիտական գիտությունների դոկտոր</dd>
                    </div>
                    <div>
                        <dt>Աշխատանքային փորձ և ստաժ</dt>
                        <dd data-cv="experience">Չի լրացվել</dd>
                    </div>
                    <div>
                        <dt>Լեզուների իմացություն</dt>
                        <dd data-cv="languages">Չի լրացվել</dd>
                    </div>
                    <div>
                        <dt>Սերտիֆիկատներ</dt>
                        <dd data-cv="certificates">Չի լրացվել</dd>
                    </div>
                    <div>
                        <dt>Դասավանդվող առարկաներ</dt>
                        <dd data-cv="subjects">Չի լրացվել</dd>
                    </div>
                </dl>
            </div>
        </section>
    </div>
    <div id="research-panel" role="tabpanel" aria-labelledby="tab-research" hidden>
        <section class="inner-section compact" id="">
            <div class="wrap"><h2 class="display-inner">Գիտական գործունեություն</h2>
                <p>Դասախոսի կողմից ներկայացված և լիազորված օգտվողի կողմից հաստատված հրապարակումներ։</p>
                <h3>Հոդվածներ</h3>
                <ul class="publication-list" data-publications="article"></ul>
                <h3>Գրքեր</h3>
                <ul class="publication-list" data-publications="book"></ul>
            </div>
        </section>
    </div>
</main>