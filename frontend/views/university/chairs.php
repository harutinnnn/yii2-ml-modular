<?php

use common\components\NumberHelper;
use common\models\TranslationModel;

?>
<section class="inner-section">
    <div class="wrap">


        <?php if (isset($chairs) && !empty($chairs)): ?>
            <?php foreach ($chairs as $i => $chair): ?>
                <?php
                $url = '/' . Yii::$app->globalData->lang . '/about-the-university/chair/' . $chair->id;
                ?>

                <a class="list-row" href="<?= $url ?>">
                    <div class="date"><?= NumberHelper::numberZeroFill($i + 1) ?></div>
                    <div>
                        <h3><?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang) ?></h3>
                        <p>
                            <?= TranslationModel::getTranslation($chair, Yii::$app->globalData->lang, 'text') ?>
                        </p>
                    </div>
                    <span class="arrow">→</span>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</section>
