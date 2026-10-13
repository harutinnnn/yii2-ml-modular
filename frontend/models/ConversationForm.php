<?php

namespace frontend\models;

use Codeception\Lib\Console\Message;
use common\components\FileHelper;
use common\models\ConversationMembers;
use common\models\Conversations;
use common\models\MessageFiles;
use common\models\MessageReads;
use common\models\Messages;
use Yii;
use yii\base\ErrorException;
use yii\base\Model;

class ConversationForm extends Model
{

    public $message;
    public $conversationId;
    public $files;


    public function rules()
    {
        return [
            [['message', 'conversationId'], 'required'],
            ['message', 'string', 'max' => 255, 'min' => 3],
            ['conversationId', 'integer'],
            [
                ['conversationId'],
                'exist',
                'targetClass' => Conversations::class,
                'targetAttribute' => ['conversationId' => 'id'],
                'message' => 'Conversation not found.',
            ],
            [['files'], 'file',
                'skipOnEmpty' => true,
                'extensions' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'],
                'maxFiles' => 10,
                'maxSize' => 10 * 1024 * 1024,
            ],
        ];
    }


    public function save($uploadedFiles = []): mixed
    {

        $transaction = Yii::$app->db->beginTransaction();


        try {

            $message = new Messages();
            $message->conversation_id = $this->conversationId;
            $message->message = $this->message;
            $message->sender_id = Yii::$app->getUser()->getId();
            $message->save(false);

            if (!empty($uploadedFiles)) {
                foreach ($uploadedFiles as $i => $file) {
                    $tmpType = $file['type'];


                    $messageFile = new MessageFiles();
                    $messageFile->message_id = $message->id;
                    $messageFile->file = $file['url'];
                    $messageFile->name = $file['name'];
                    $messageFile->type = $file['type'];
                    $messageFile->save(false);
                    $uploadedFiles[$i]['icon'] = FileHelper::getFileTypeIcon($tmpType, $file['url']);
                }
            }

            $conversationUsers = ConversationMembers::find()->where(['conversation_id' => $this->conversationId])->all();
            if (!empty($conversationUsers)) {
                foreach ($conversationUsers as $conversationUser) {
                    if ($conversationUser->user_id != Yii::$app->getUser()->getId()) {
                        $message_reads = new MessageReads();
                        $message_reads->message_id = $message->id;
                        $message_reads->user_id = $conversationUser->user_id;
                        $message_reads->save(false);
                    }
                }
            }

            $transaction->commit();

        } catch (\Throwable $e) {
            $transaction->rollBack();
            return false;
        }

        $messageUser = $message->user->attributes ?? null;

        $messageUser['full_name'] = $message->user->additional->first_name . ' ' . $message->user->additional->last_name;

        $tmpMessage = $message->attributes;
        $tmpMessage['created_at'] = date('Y-m-d H:i:s');

        return [
            'message' => $tmpMessage,
            'user' => $messageUser ?? null,
            'uploadedFiles' => $uploadedFiles ?? [],
        ];
    }


    protected function checkConversationMember(): bool
    {
        return ConversationMembers::find()
            ->where(
                [
                    'conversation_id' => $this->conversationId,
                    'user_id' => Yii::$app->user->id
                ]
            )
            ->exists();

    }
}