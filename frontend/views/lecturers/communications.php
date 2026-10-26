<main class="module-main">
    <section class="module-hero">
        <div>
            <div class="eyebrow">Ներքին հաղորդակցում</div>
            <h1>Նամակագրություն</h1>
            <p>Անհատական և permission-based խմբային հաղորդագրություններ՝ ֆայլերով և կարգավիճակներով։</p></div>
        <div class="status-box"><strong>3 չկարդացված</strong><span>1 կարևոր · 2 սովորական</span></div>
    </section>

    <section class="module-section message-layout">
        <aside class="panel">

            <?php use common\components\FileHelper;
            use common\components\I18n;
            use yii\helpers\Html;

            foreach ($conversations as $conversation): ?>

                <?php if ($conversationId == $conversation->id): ?>

                    <button class="btn"><?= I18n::translate('new_message') ?></button>
                    <?= Html::hiddenInput('conversationId', $conversation->id, ['id' => 'conversationId']) ?>
                <?php endif; ?>

                <a class="list-item <?= $conversationId == $conversation->id ? 'active' : '' ?>"
                   href="/am/lecturer/communications?conversationId=<?= $conversation->id ?>">
                    <?= $conversation->title ?>

                </a>

            <?php endforeach; ?>

            <p class="meta">Խմբի պահպանում՝ 1 ամիս · հիշեցում ջնջումից 2 ժամ առաջ</p>
        </aside>

        <div class="panel">
            <?php if (isset($currentConversation) && !empty($currentConversation)): ?>
                <span class="badge">Կարդացված</span>
                <h2 class="d-flex">
                    <?= $currentConversation->title ?>

                    <?php if ($conversation->moderator == Yii::$app->user->id): ?>
                        <div class="moderate-chat module-user fs-18px" target="_blank"
                              style="margin-left: auto">
                                <a href="/admin/conversations/members?conversation_id=<?= $currentConversation->id ?>" target="_blank">
                                    Manage users
                                </a>
                        </div>
                    <?php endif; ?>
                </h2>

                <p class="meta">
                    Պատասխանատու՝ <?= $currentConversation->creator_->additional->first_name . ' ' . $currentConversation->creator_->additional->last_name ?>
                    · ստեղծվել է <?= date('d.m.Y', strtotime($currentConversation->created_at)) ?>
                </p>
                <div class="notice">
                    <strong>Կարևոր</strong>
                    <br>Խնդրում ենք մինչև օգոստոսի 22-ը ներկայացնել գիտական
                    ակտիվության տվյալները։
                </div>
                <form data-demo-form>
                    <div id="user-messages" class="user-messages">
                        <?php if (!empty($messages)): ?>
                            <?php foreach ($messages as $message): ?>
                                <div class="user-message-item <?= $message->sender_id == Yii::$app->getUser()->getId() ? 'current-user' : '' ?>">
                                    <div class="user-message-item-header">
                                        <strong class="user-message-item-name">
                                            <?= $message->user->additional->first_name . ' ' . $message->user->additional->last_name ?>
                                        </strong>
                                        <div class="user-message-date">
                                            <?= $message->created_at ?>
                                        </div>
                                    </div>

                                    <div>
                                        <?= $message->message ?>
                                    </div>
                                    <div class="message-files">
                                        <?php if ($message->files): ?>
                                            <?php foreach ($message->files as $fileItem): ?>
                                                <div class="message-file-item">

                                                    <a
                                                            href="<?= $fileItem->file ?>"
                                                            target="_blank"
                                                            data-toggle="tooltip"
                                                            data-placement="top"
                                                            title="<?= $fileItem->name ?>"

                                                    >
                                                        <img src="<?= FileHelper::getFileTypeIcon($fileItem->type, $fileItem->file) ?>"
                                                             alt="">
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <label class="field">Պատասխան
                        <textarea id="chat-message"></textarea>
                    </label>


                    <label class="field">
                        Կցել ֆայլ
                        <input type="file" id="message-attachments" multiple>
                    </label>
                    <button class="btn" type="button" onclick="sendMessage(this,<?= $currentConversation->id ?? 0 ?>)">
                        Ուղարկել
                    </button>

                    <div class="notice hidden" data-confirmation id="conversation-messages">
                        Հաղորդագրությունն ուղարկվել է։ Կարգավիճակ՝ առաքված։
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php

$this->registerJs(<<<JS

    const conversationId = {$conversationId};

JS, \yii\web\View::POS_HEAD);


$this->registerJsFile(
        '@web/js/messages.js?' .
        filesize(Yii::getAlias('@frontend/web/js/messages.js')),
        [
                'depends' => [\yii\web\JqueryAsset::class],
                'position' => \yii\web\View::POS_END,
        ]
);

?>