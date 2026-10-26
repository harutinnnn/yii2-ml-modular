<?php

namespace frontend\controllers;

use common\components\I18n;
use common\models\Conversations;
use frontend\models\ConversationForm;
use frontend\models\StudentLoginForm;
use stdClass;
use Yii;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class ConversationController extends MyController
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionSendMessage()
    {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $obj = new stdClass();
        $obj->success = true;
        $obj->message = "";
        $obj->messageObj = null;
        $obj->errors = [];


        $conversationForm = new ConversationForm();

        if ($conversationForm->load(Yii::$app->request->post())) {


            $checkConversation = Conversations::find()
                ->innerJoin('conversation_members cm', 'cm.conversation_id = conversations.id')
                ->where(['conversations.id' => $conversationForm->conversationId, 'cm.user_id' => Yii::$app->user->id])
                ->one();

            if (!empty($checkConversation)) {


                $conversationForm->files = UploadedFile::getInstances(
                    $conversationForm,
                    'files'
                );


                if ($conversationForm->validate()) {


                    $uploadedFiles = [];

                    foreach ($conversationForm->files as $file) {
                        $fileName = Yii::$app->security->generateRandomString(32)
                            . '.' . strtolower($file->extension);


                        $datePath = date('Y_m_d');
                        $path = Yii::getAlias('@frontend/web/uploads/messages/' . $datePath . '/');
                        if (!is_dir($path)) {
                            mkdir($path, 0777, true);
                        }


                        if ($file->saveAs($path . $fileName)) {

                            $uploadedFiles[] = [
                                'name' => $file->name,
                                'url' => '/uploads/messages/' . $datePath . '/' . $fileName,
                                'type' => $file->extension,
                            ];
                        }
                    }

                    if ($message = $conversationForm->save($uploadedFiles)) {

                        $obj->messageObj = $message;


                    } else {

                        $obj->success = false;
                        $obj->message = I18n::translate('some_thing_get_wrong');
                    }
                } else {
                    $obj->errors = $conversationForm->errors;
                    $obj->success = false;
                }
            }else{
                $obj->success = false;
                $obj->message = I18n::translate('conversation_permission_denied');
            }

        } else {

            $obj->errors = $conversationForm->errors;
            $obj->success = false;
        }


        return $obj;

    }


}
