<?php

namespace frontend\controllers;

use common\components\UserRoles;
use common\helpers\I18n;
use common\models\Chairs;
use common\models\Faculties;
use common\models\User;
use frontend\models\StudentLoginForm;
use Yii;

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


        $faculty = Faculties::findOne($user->additional->faculty);
        $chair = Chairs::findOne($user->additional->chair);

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
