<?php

use common\components\I18n;
use common\models\TranslationModel;

?>
<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow"><?= I18n::translate('library_module') ?></div>
            <h1><?= I18n::translate('centralized_access_to_knowledge') ?></h1>
            <p><?= I18n::translate('library_module_header_text') ?></p>
        </div>
        <div class="status-box">
            <strong>Երկ-Ուրբ 09:00-18:00</strong>
            <span><?= Yii::$app->globalData->settings['library_email_address'] ?> · Նալբանդյան 128</span>
        </div>
    </section>
    <section id="digital" class="module-section"><h2><?= I18n::translate('digital_library') ?></h2>
        <div class="cards">
            <?php
            if (isset($libraryModules) && !empty($libraryModules)): ?>
                <?php foreach ($libraryModules as $i => $libraryModule): ?>
                    <div class="panel">
                        <h3><?= TranslationModel::getTranslation($libraryModule, Yii::$app->globalData->lang) ?></h3>
                        <?= TranslationModel::getTranslation($libraryModule, Yii::$app->globalData->lang, 'text') ?>
                        <a class="btn <?= $i > 0 ? 'secondary' : '' ?>"
                           href="/<?= Yii::$app->globalData->lang ?>/library/<?= $libraryModule->slug ?>">
                            Բացել
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </section>
    <section id="resources" class="module-section"><h2><?= I18n::translate('online_resources') ?></h2>
        <ul class="checklist">
            <li>Գրադարանային կատալոգներ</li>
            <li>Էլեկտրոնային շտեմարաններ</li>
            <li>Բաց հասանելիության գիտական բազաներ</li>
            <li>ՀՊՏՀ ամսագրեր</li>
        </ul>
    </section>
    <section id="services" class="module-section"><h2>Առցանց ծառայություններ</h2>
        <div class="cards">
            <div class="panel"><h3>Պատվիրել գիրք</h3>
                <p>Ամրագրում և առձեռնացման ժամի ընտրություն։</p></div>
            <div class="panel"><h3>Էլեկտրոնային ֆայլ</h3>
                <p>Իրավասությամբ հասանելի թվային պատճեններ։</p></div>
            <div class="panel"><h3>Ընթերցողի հաշիվ</h3>
                <p>Պատվերներ, ժամկետներ և պատմություն։</p></div>
        </div>
    </section>
    <section id="users" class="module-section"><h2>Օգտատերերի համար</h2>
        <p>Ուսանողների, նոր ընթերցողների և այցելուների գրանցման, օգտագործման ու սպասարկման ուղեցույցներ։</p></section>
    <section id="about" class="module-section"><h2>Գրադարանի մասին</h2>
        <p>Պատմություն · ծառայություններ · կանոններ · հասցե և քարտեզ · սոցիալական մեդիա</p><a class="btn secondary"
                                                                                              href="contact.html#map">Քարտեզ</a>
    </section>
</main>