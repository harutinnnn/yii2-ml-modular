<?php
/** @var \common\models\EducationLevels $educationLevel */

use common\components\I18n;

$content = $this->context->pageData['content'];

?>

<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('sections') ?></div>
        <h2 class="display-inner"><?= I18n::translate('educational_programs') ?></h2>
        <div class="cards">
            <?php if (isset($educationLevels) && !empty($educationLevels)): ?>
                <?php
                $count = 1;
                ?>
                <?php foreach ($educationLevels as $i => $educationLevel): ?>

                    <article class="card">
                        <div class="card-number"><?= '0' . ($count++) ?></div>
                        <h3><?= $educationLevel->getTranslation(Yii::$app->globalData->lang)->title ?></h3>
                        <p><?= $educationLevel->getTranslation(Yii::$app->globalData->lang)->text ?></p>
                        <?php if (isset($levelAttachedMenus[$educationLevel->m_id]->url)): ?>
                            <a href="/<?= Yii::$app->globalData->lang ?>/education/<?= $levelAttachedMenus[$educationLevel->m_id]->url ?>">
                                <?= I18n::translate('open') ?> →
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('sections') ?></div>
        <h2 class="display-inner"><?= I18n::translate('continuing_education') ?></h2>
        <div class="cards">
            <?php if (isset($continuingEducationPrograms) && !empty($continuingEducationPrograms)): ?>
                <?php foreach ($continuingEducationPrograms as $i => $continuingEducationProgram): ?>

                    <?php
                    $index = $i + 1;
                    $index = strlen($index) == 1 ? '0' . $index : $index;
                    ?>
                    <article class="card">
                        <div class="card-number"><?= $index ?></div>
                        <h3><?= $continuingEducationProgram->getTranslation(Yii::$app->globalData->lang)->title ?></h3>
                        <p><?= $continuingEducationProgram->getTranslation(Yii::$app->globalData->lang)->desc ?></p>
                        <?php
                        $menuUrl = "";

                        if (isset($levelAttachedMenus[$educationLevels[$continuingEducationProgram->education_level]->m_id]->url)) {
                            $menuUrl = $levelAttachedMenus[$educationLevels[$continuingEducationProgram->education_level]->m_id]->url;
                        }

                        ?>
                        <a href="/<?= Yii::$app->globalData->lang ?>/education/<?= $menuUrl ?>/program/<?= $continuingEducationProgram->id ?>">
                            <?= I18n::translate('open') ?> →
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<section class="inner-section compact" id="">
    <div class="wrap"><h2 class="display-inner">Դասացուցակներ և քննագրաֆիկներ</h2>
        <div class="section-directory"><a href="schedules.html">Դասացուցակներ</a><a href="exam-schedules.html">Քննագրաֆիկներ</a>
        </div>
    </div>
</section>
<section class="inner-section compact" id="">
    <div class="wrap"><h2 class="display-inner">Վիդեո դասախոսություններ</h2><a class="btn-outline"
                                                                               href="video-lectures.html">Դիտել
            վիդեո դասախոսությունները →</a></div>
</section>
