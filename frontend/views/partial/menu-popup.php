<div aria-hidden="true" class="menu-panel">
    <button aria-label="Փակել մենյուն" class="menu-close" data-menu-close="">×</button>
    <a class="menu-logo" href="/"><img alt="ՀՊՏՀ" src="/images/logo_am.svg"/></a>
    <div class="two-step-menu">
        <div class="two-step-main">
            <div class="two-step-kicker">Բաժիններ</div>

            <?php if (isset($this->context->pageData['mainMenuItems']) && !empty($this->context->pageData['mainMenuItems'])): ?>
                <?php foreach ($this->context->pageData['mainMenuItems'] as $mainMenuItem): ?>
                    <button class="menu-main-item active" data-menu-index="<?= $mainMenuItem['menu']->id ?>"
                            type="button">
                        <span><?= $mainMenuItem['menu']->getTranslation(Yii::$app->globalData->lang)->title ?></span><b>→</b>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div class="two-step-sub">
            <?php if (isset($this->context->pageData['mainMenuItems']) && !empty($this->context->pageData['mainMenuItems'])): ?>
                <?php foreach ($this->context->pageData['mainMenuItems'] as $mainMenuItem): ?>
                    <?php if (isset($mainMenuItem['nodes'])): ?>
                        <div class="two-step-panel" data-menu-panel="<?= $mainMenuItem['menu']->id ?>">
                            <?php foreach ($mainMenuItem['nodes'] as $node): ?>

                                <!--                            <div class="two-step-panel active" data-menu-panel="--><?php //= $mainMenuItem['menu']->id ?><!--">-->
                                <div class="submenu-col">
                                    <h4><?= $node['menu']->getTranslation(Yii::$app->globalData->lang)->title ?></h4>
                                    <?php if (isset($node['nodes'])): ?>
                                        <?php foreach ($node['nodes'] as $subNode): ?>
                                            <a href="/<?= Yii::$app->globalData->lang ?>/<?= $mainMenuItem['menu']->url ?>/<?= $subNode['menu']->url ?>">
                                                <?= $subNode['menu']->getTranslation(Yii::$app->globalData->lang)->title ?>
                                            </a>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>


        </div>
    </div>
    <div class="menu-bottom">
        <span>Հայաստանի պետական տնտեսագիտական համալսարան</span><span>Նալբանդյան 128, Երևան</span><span>HY · EN · RU</span>
    </div>
</div>