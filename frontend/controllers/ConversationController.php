<?php

namespace frontend\controllers;

use common\components\EntityTypes;
use common\components\I18n;
use common\components\UserRoles;
use common\models\ActionLogs;
use common\models\ApplicationsAndInquiries;
use common\models\Chairs;
use common\models\Conversations;
use common\models\Faculties;
use common\models\ScientificPortfolio;
use common\models\User;
use frontend\models\ConversationForm;
use frontend\models\ScientificPortfolioForm;
use frontend\models\StudentDataForm;
use frontend\models\StudentLoginForm;
use stdClass;
use Yii;
use yii\filters\VerbFilter;
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


        } else {

            $obj->errors = $conversationForm->errors;
            $obj->success = false;
        }


        return $obj;

    }


}
