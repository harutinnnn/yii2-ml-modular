<?php
$menu = $this->context->pageData['menuObj'];
?>
<nav class="inner-subnav">
    <div class="wrap"><a href="#programs">Ծրագրեր</a><a href="admission.html">Ընդունելություն</a></div>
</nav>
<section class="inner-section" id="programs">
    <div class="wrap">
        <div class="listing-toolbar">
            <div class="filter-group">
                <button class="filter-pill active">Բոլորը</button>
                <button class="filter-pill">Առկա</button>
                <button class="filter-pill">Հեռակա</button>
            </div>

            <input class="search-mini" placeholder="Որոնել ծրագիր"></div>

        <?php if (isset($programs) && !empty($programs)): ?>

            <?php foreach ($programs as $program): ?>
                <a class="list-row"
                   href="/<?= Yii::$app->globalData->lang ?>/education/<?= $menu->url ?? '' ?>/program/<?= $program->id ?>">
                    <div class="date">01</div>

                    <div>
                        <h3><?= $program->getTranslation(Yii::$app->globalData->lang)->title ?></h3>

                        <?= $level->getTranslation(Yii::$app->globalData->lang)->title ?>
                        · <?= $program->duration_by_year ?>
                        <?= \common\components\I18n::translate('year') ?>
                        <?php
                        $tmpLang = [];
                        $progLanguages = explode(',', $program->languages);
                        foreach ($progLanguages as $progLanguage) {
                            if (isset($langs[trim($progLanguage)])) {
                                $tmpLang[] = $langs[trim($progLanguage)];
                            }
                        }

                        if (!empty($tmpLang)) {

                            echo '· ' . implode(' / ', $tmpLang);
                        }
                        ?>

                    </div>
                    <span class="arrow">→</span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>
<section class="rich-photo-section">
    <div class="wrap">
        <div class="photo-feature-split">
            <div class="visual"><img src="/images/editorial/reading-students.jpg" alt="ՀՊՏՀ ուսանողներ"></div>
            <div class="photo-feature-copy">
                <div class="eyebrow red">Կրթական միջավայր</div>
                <h2>Գիտելիքը կապվում է փորձի, հետազոտության և համայնքի հետ</h2>
                <p>Ծրագրերը ձևավորվում են այնպես, որ ուսանողները կարողանան համադրել տեսական հիմքը, կիրառական
                    առաջադրանքները և մասնագիտական կապերը։</p><a class="btn-red" href="education.html">Դիտել կրթական
                    ծրագրերը →</a></div>
        </div>
    </div>
</section>