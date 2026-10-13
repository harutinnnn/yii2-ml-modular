<?php

namespace console\controllers;

use common\models\ConversationMembers;
use Yii;
use yii\console\Controller;
use Workerman\Worker;
use PHPSocketIO\SocketIO;

class SocketController extends Controller
{
    public function actionStart()
    {

        $io = new SocketIO(3001);

        $io->on('connection', function ($socket) use ($io) {


            // Join user-specific room
            $socket->on('join', function ($userId) use ($socket) {
                print_r('join:' . $userId);
                // Demo only: verify identity before joining
                $socket->join("user:$userId");
            });

            // Private message
            $socket->on('private-message', function ($data) use ($io) {


                $conversation_id = $data['data']['messageObj']['message']['conversation_id'] ?? 0;
                $conversationUsers = ConversationMembers::find()->where(['conversation_id' => $conversation_id])->all();
                foreach ($conversationUsers as $conversationUser) {
                    if ($conversationUser->user_id != $data['userId']) {
                        $io->to("user:{$conversationUser->user_id}")
                            ->emit('message', $data['data']);
                    }
                }


            });


            // Broadcast
            $socket->on('broadcast', function ($message) use ($io) {
                $io->emit('notification', $message);
                print_r($message);
            });

            $socket->on('global-message', function ($message) use ($io) {
                $io->emit('global-message', $message);
                print_r($message);
            });
        });

        Worker::runAll();
    }
}