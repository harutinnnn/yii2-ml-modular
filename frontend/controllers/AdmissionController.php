<?php

namespace frontend\controllers;

use backend\modules\user\models\ApplicantForm;
use common\helpers\I18n;
use common\models\Admissions;
use common\models\Chairs;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\Faculties;
use common\models\Menu;
use frontend\models\AdmissionForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
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


            $admissions = new Admissions();
            $admissions->name = $admissionForm->name;
            $admissions->surname = $admissionForm->surname;
            $admissions->email = $admissionForm->email;
            $admissions->phone = $admissionForm->phone;
            $admissions->education_level = $admissionForm->education_level;
            $admissions->educational_programs = $admissionForm->educational_programs;
            $admissions->created_at = time();
            $admissions->updated_at = time();

            if ($admissions->save()) {

                //TODO send email...?


                Yii::$app->session->setFlash('admission_successfully_sent','Yor admission successfully sent!');


                $this->redirect(['admission/online-application']);

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
