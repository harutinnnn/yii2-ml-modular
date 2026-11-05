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
        <aside>
            <div class="meta-label"><?= I18n::translate('quick_links') ?></div>

            <div class="link-list">
                <a href="education.html">
                    <span>
                        Կրթական ծրագրեր
                    </span>
                </a>
                <a href="structure.html">
                    <span>
                        Ֆակուլտետներ և ամբիոններ
                    </span>
                </a>
                <a href="contact.html">
                    <span>
                        Գրել համալսարանին
                    </span>
                </a>
            </div>
        </aside>
    </div>
</section>

<section class="inner-section white">
    <div class="wrap">
        <div class="eyebrow red"><?= I18n::translate('sections') ?></div>
        <h2 class="display-inner"><?= I18n::translate('educational_programs') ?></h2>
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
                        <a href="/<?= Yii::$app->globalData->lang ?>/education/education/program/<?= $educationalProgram->educationProgramm->id ?>"><?= I18n::translate('open') ?>
                            →</a>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="inner-section compact white" id="staff">
    <div class="wrap">
        <!--TODO -->
        <?php if (isset($chair->departmentHead) && !empty($chair->departmentHead)): ?>
            <?php
            $userAdditional = $chair->departmentHead->additional;
            ?>
            <div class="department-head">
                <div class="meta-label"><?= I18n::translate('department_leadership') ?></div>
                <h2><?= I18n::translate('head_of_department') ?></h2>
                <p><?= $userAdditional->teacher_academic_degree ?? '' ?>
                    · <?= $userAdditional->teacher_position ?? '' ?></p>
                <a class="btn-outline"
                   href="/<?= Yii::$app->globalData->lang ?>/about-the-university/head-of-department/profile/<?= $chair->departmentHead->id ?? 0 ?>">
                    <?= I18n::translate('view_profile') ?> →
                </a>
            </div>
        <?php endif; ?>

        <h2 class="display-inner"><?= I18n::translate('academic_staff') ?></h2>
        <ul class="staff-list">

            <?php if (isset($staff) && !empty($staff)): ?>
                <?php foreach ($staff as $staffItem): ?>
                    <?php if (isset($staffItem->user)): ?>
                        <?php
                        $user = $staffItem->user ?? null;
                        $additional = $staffItem->user->additional ?? null;

                        $url = '/' . Yii::$app->globalData->lang . '/about-the-university/teacher/profile/' . $user->id;

                        ?>
                        <li>
                            <a href="<?= $url ?>">
                                <strong><?= $additional->first_name . ' ' . $additional->last_name ?></strong>
                                <span><?= $additional->teacher_academic_degree ?> · <?= $additional->teacher_position ?></span>
                                <span aria-hidden="true">→</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
</section>