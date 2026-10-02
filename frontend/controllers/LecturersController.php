<?php

namespace frontend\controllers;

use common\components\UserRoles;
use common\helpers\I18n;
use common\models\Chairs;
use common\models\Faculties;
use common\models\User;
use frontend\models\TeacherLoginForm;
use Yii;

/**
 * Site controller
 */
class LecturersController extends MyController
{
    public function beforeAction($action)
    {

        // Your check
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        } else {

            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);

            if (!isset($roles[UserRoles::TEACHER])) {
                return $this->redirect('/' . Yii::$app->globalData->lang . '/lecturers/teacher-login');
            }

        }

        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionPersonalDashboard()
    {


        $this->layout = 'teacher';

        $userId = Yii::$app->user->id;

        $roles = Yii::$app->authManager->getRolesByUser($userId);

        $user = User::findOne($userId);

        //Check if user role not student then redirect yo home
        if (!isset($roles[UserRoles::TEACHER])) {
            return $this->redirect('/' . Yii::$app->globalData->lang);
        }


        $faculty = Faculties::findOne($user->additional->faculty);
        $chair = Chairs::findOne($user->additional->chair);

        return $this->render('personal-dashboard',
            [
                'user' => $user,
                'faculty' => $faculty,
                'chair' => $chair,
            ],
        );
    }
}
