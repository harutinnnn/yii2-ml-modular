<?php

use common\components\NumberHelper;
use common\models\TranslationModel;

?>
<section class="inner-section" id="list">
    <div class="wrap">


        <?php

        if (isset($faculties) && !empty($faculties)): ?>
            <?php foreach ($faculties as $i => $faculty): ?>
                <?php
                $url = '/' . Yii::$app->globalData->lang . '/about-the-university/faculties/' . $faculty->id;
                ?>

                <a class="list-row" href="<?= $url ?>">
                    <div class="date"><?= NumberHelper::numberZeroFill($i + 1) ?></div>

                    <div>
                        <h3>
                            <?= TranslationModel::getTranslation($faculty, Yii::$app->globalData->lang) ?>
                        </h3>
                        <p>
                            <?= TranslationModel::getTranslation($faculty, Yii::$app->globalData->lang, 'text') ?>
                        </p>
                    </div>

                    <span class="arrow">→</span>
                </a>

            <?php endforeach; ?>
        <?php endif; ?>


    </div>
</section>
