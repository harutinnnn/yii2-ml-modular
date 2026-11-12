<section class="inner-section">
    <div class="wrap">
        <div class="listing-toolbar">
            <div class="filter-group">
                <button class="filter-pill active">Բոլորը</button>
                <button class="filter-pill">2026</button>
                <button class="filter-pill">2025</button>
            </div>
            <input class="search-mini" placeholder="Որոնել">
        </div>

        <?php use common\models\TranslationModel;

        if (isset($libraryItems) && !empty($libraryItems)): ?>
            <?php foreach ($libraryItems as $i => $libraryItem): ?>

                <div class="publication">
                    <small>2026 · ՀՊՏՀ</small>
                    <h4><?= TranslationModel::getTranslation($libraryItem,Yii::$app->globalData->lang) ?></h4>
                    <p class="body-copy" style="font-size:14px">
                        <?= TranslationModel::getTranslation($libraryItem,Yii::$app->globalData->lang,'text') ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<section class="rich-photo-section">
    <div class="wrap">
        <div class="publication-showcase">
            <div class="publication-photo"><img src="/images/editorial/reading-students.jpg" alt="ՀՊՏՀ գրադարան">
            </div>
            <div>
                <div class="eyebrow red">Հրատարակություններ</div>
                <h2 class="display-inner">Գիտական միտքը՝ ընթերցվող և հասանելի ձևով</h2>
                <div class="cover-grid">
                    <div class="fake-cover"><small>ՀՊՏՀ գիտական հանդես</small><strong>Տնտեսություն և
                            հասարակություն</strong><span>2026 · №2</span></div>
                    <div class="fake-cover"><small>Research papers</small><strong>Applied Economics
                            Review</strong><span>Vol. 14</span></div>
                    <div class="fake-cover"><small>Հետազոտություն</small><strong>Հայաստանի տնտեսության
                            դիտարկումներ</strong><span>2026</span></div>
                </div>
            </div>
        </div>
    </div>
</section>