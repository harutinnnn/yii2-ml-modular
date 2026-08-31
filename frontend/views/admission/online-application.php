<?php

use common\components\I18n;

$content = $this->context->pageData['content'];
?>
<section class="inner-section">
    <div class="wrap inner-grid-2">
        <div>
            <div class="eyebrow red"><?= I18n::translate('applicant_form') ?></div>
            <h2 class="display-inner"><?= I18n::translate('start_application') ?></h2>
            <form class="form-grid">
                <div class="field"><label><?= I18n::translate('name') ?></label><input/></div>
                <div class="field"><label><?= I18n::translate('surname') ?></label><input/></div>
                <div class="field"><label><?= I18n::translate('email') ?></label><input type="email"/></div>
                <div class="field"><label><?= I18n::translate('phone') ?></label><input/></div>
                <div class="field"><label for="education_level"><?= I18n::translate('education_level') ?></label>
                    <select name="education_level" id="education_level">
                        <?php if (isset($education_levels) && !empty($education_levels)): ?>
                            <?php foreach ($education_levels as $education_level_id => $education_level_title): ?>
                                <option value="<?= $education_level_id ?>"><?= $education_level_title ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="field"><label for="program"><?= I18n::translate('program') ?></label>
                    <select id="program" name="program">
                        <option>Ընտրել ծրագիր</option>
                    </select>
                </div>
                <div class="field full">
                    <label>
                        <input type="checkbox" name="consent_processing_personal_data"/>
                        <?= I18n::translate('iconsent_processing_personal_data') ?>
                    </label>
                </div>
                <div class="field full">
                    <button class="btn-red" type="button"><?= I18n::translate('continue_application') ?> →</button>
                </div>
            </form>
        </div>
        <aside>
            <div class="meta-label">Ինչ է պետք</div>
            <div class="facts-list">
                <div><strong>01</strong><span>Անձը հաստատող փաստաթուղթ</span></div>
                <div><strong>02</strong><span>Կրթության մասին փաստաթուղթ</span></div>
                <div><strong>03</strong><span>Ընտրված ծրագրին համապատասխան հավելյալ նյութեր</span></div>
            </div>
        </aside>
    </div>
</section>
<section class="rich-photo-section">
    <div class="wrap">
        <div class="photo-feature-split">
            <div class="visual"><img src="/images/editorial/reading-students.jpg" alt="ՀՊՏՀ ուսանողներ"></div>
            <div class="photo-feature-copy">
                <div class="eyebrow red"><?= I18n::translate('educational_environment') ?></div>
                <h2><?= $content->getTranslation(Yii::$app->globalData->lang)->title ?? '' ?></h2>
                <?= $content->getTranslation(Yii::$app->globalData->lang)->text ?? '' ?>

                <a class="btn-red" href="/<?= Yii::$app->globalData->lang ?>/education/all-programs">
                    <?= I18n::translate('view_educational_programs') ?> →
                </a>
            </div>
        </div>
    </div>
</section>