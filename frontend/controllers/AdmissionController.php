<?php

namespace frontend\controllers;

use backend\modules\user\models\ApplicantForm;
use common\components\UserRoles;
use common\helpers\I18n;
use common\models\Admissions;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\User;
use common\models\UserAdditionalData;
use common\models\UserAdmissionData;
use frontend\models\AdmissionForm;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Site controller
 */
class AdmissionController extends MyController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionOnlineApplication()
    {

        $admissionForm = new AdmissionForm();

        if ($admissionForm->load(Yii::$app->request->post()) && $admissionForm->validate()) {

            $transaction = Yii::$app->db->beginTransaction();
            $isError = false;

            try {

                $user = new User();
                $user->status = User::STATUS_PENDING;
                $user->email = $admissionForm->email;
                $user->generateAuthKey();

                $password = Yii::$app->security->generateRandomString(12);

                $user->setPassword($password);

                if ($user->save()) {


                    $auth = Yii::$app->authManager;

                    $role = $auth->getRole(UserRoles::APPLICANT);
                    $auth->assign($role, $user->id);

                    $additional = new UserAdditionalData();
                    $additional->first_name = $admissionForm->name;
                    $additional->dob = $admissionForm->dob;
                    $additional->last_name = $admissionForm->surname;
                    $additional->phone = $admissionForm->phone;
                    $additional->user_id = $user->id;

                    if (!$additional->save()) {
                        $transaction->rollBack();
                        throw new Exception("Some thong get wrong [UserAdditionalData]");
                    } else {
                        $userAdmissionData = new UserAdmissionData();
                        $userAdmissionData->user_id = $user->id;
                        $userAdmissionData->education_level = $admissionForm->education_level;
                        $userAdmissionData->educational_programs = $admissionForm->educational_programs;
                        $userAdmissionData->created_at = time();
                        $userAdmissionData->updated_at = time();

                        if (!$userAdmissionData->save()) {
                            $transaction->rollBack();
                            throw new Exception("Some thong get wrong [UserAdmissionData]");
                        }

                        $transaction->commit();


                        //TODO send email...?
                        Yii::$app->session->setFlash('admission_successfully_sent', 'Yor admission successfully sent!');

                        return $this->redirect([Yii::$app->globalData->lang . '/admission/online-application']);
                    }

                } else {
                    $transaction->rollBack();
                    throw new Exception("Some thong get wrong [User]");
                }

            } catch (\Throwable $e) {

                $transaction->rollBack();
                throw $e; // or handle the error appropriately
            }


        }


        return $this->render('online-application',
            [
                'model' => $admissionForm,
                'education_levels' => ArrayHelper::map(EducationLevels::find()->all(),
                    'id',
                    function ($educationLevel) {
                        return $educationLevel->getTranslation(Yii::$app->globalData->lang)->title;
                    }),
            ]);
    }


    public function actionEducationPrograms()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = Yii::$app->request->get('id');

        $programms = ArrayHelper::map(EducationalPrograms::find()->where(['education_level' => intval($id)])->all(), 'id', function ($model) {
            return $model->getTranslation(Yii::$app->globalData->lang)->title;
        });

        return $programms;


    }
}
