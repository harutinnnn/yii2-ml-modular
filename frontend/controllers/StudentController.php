<?php

namespace frontend\controllers;

use backend\modules\user\models\ApplicantForm;
use common\components\UserRoles;
use common\helpers\I18n;
use common\models\Admissions;
use common\models\Chairs;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\EducationPlan;
use common\models\Faculties;
use common\models\Language;
use common\models\Menu;
use common\models\Student;
use common\models\User;
use frontend\models\AdmissionForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\StudentLoginForm;
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
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class StudentController extends MyController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect('/' . Yii::$app->globalData->lang . '/student/login');
        }

        $this->layout = 'login';


        $model = new StudentLoginForm();


        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->redirect('/' . Yii::$app->globalData->lang . '/student/student-personal-dashboard');

        }


        return $this->render('login',
            ['model' => $model,]
        );
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionStudentPersonalDashboard()
    {

        if (Yii::$app->user->isGuest) {
            return $this->redirect('/' . Yii::$app->globalData->lang . '/student/login');
        }


        $userId = Yii::$app->user->id;

        $roles = Yii::$app->authManager->getRolesByUser($userId);

        $user = User::findOne($userId);

        //Check if user role not student then redirect yo home
        if (!isset($roles[UserRoles::STUDENT])) {
            return $this->redirect('/' . Yii::$app->globalData->lang);
        }


        $faculty = Faculties::findOne($user->userAdditionalData->faculty);
        $chair = Chairs::findOne($user->userAdditionalData->chair);

        $this->layout = 'student';

        return $this->render('student-personal-dashboard',
            [
                'user' => $user,
                'faculty' => $faculty,
                'chair' => $chair,
            ],
        );
    }
}
