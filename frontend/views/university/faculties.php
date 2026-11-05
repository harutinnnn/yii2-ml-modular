<?php

use common\components\I18n;
use common\components\NumberHelper;
use common\models\TranslationModel;

?>
    <section class="inner-section" id="list">
        <div class="wrap">

            <div class="listing-toolbar">
                <div class="filter-group">
                    <a href="?education_level=0">
                        <button class="filter-pill <?= !$educationLevelId ? 'active' : '' ?>"><?= I18n::translate('all') ?></button>
                    </a>
                    <?php if (isset($educationLevels) && !empty($educationLevels)): ?>
                        <?php foreach ($educationLevels as $educationLevel): ?>
                            <a href="?education_level=<?= $educationLevel->id ?>">
                                <button class="filter-pill <?= $educationLevelId == $educationLevel->id ? 'active' : '' ?>"><?= TranslationModel::getTranslation($educationLevel, Yii::$app->globalData->lang) ?></button>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <input class="search-mini" id="search-mini" placeholder="Որոնել ֆակուլտետ">
            </div>


            <div class="filter-list">
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

        </div>
    </section>


<?php


$this->registerJs(<<<JS

 initListFilter('search-mini','.filter-list a','h3')
    
        
JS, \yii\web\View::POS_END);


?>