<?php

namespace frontend\controllers;

use common\components\EntityTypes;
use common\components\UserRoles;
use common\helpers\I18n;
use common\models\ActionLogs;
use common\models\Chairs;
use common\models\Faculties;
use common\models\User;
use frontend\models\StudentDataForm;
use frontend\models\StudentLoginForm;
use Yii;

/**
 * Site controller
 */
class StudentController extends MyController
{

    public function beforeAction($action)
    {

        // Your check
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/student-login']);
        } else {

            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);

            if (!isset($roles[UserRoles::STUDENT])) {
                return $this->redirect('/' . Yii::$app->globalData->lang . '/site/student-login');
            }

        }

        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionStudentPersonalDashboard()
    {
        $this->layout = 'student';

        $studentDataModel = new StudentDataForm();

        $userId = Yii::$app->user->id;

        $roles = Yii::$app->authManager->getRolesByUser($userId);

        $user = User::findOne($userId);

        //Check if user role not student then redirect yo home
        if (!isset($roles[UserRoles::STUDENT])) {
            return $this->redirect('/' . Yii::$app->globalData->lang);
        }

        $faculty = Faculties::findOne($user->additional->faculty);
        $chair = Chairs::findOne($user->additional->chair);


        $studentDataModel->id = $user->id;
        $studentDataModel->first_name = $user->additional->first_name;
        $studentDataModel->last_name = $user->additional->last_name;
        $studentDataModel->dob = $user->additional->dob;
        $studentDataModel->passport_details = $user->additional->passport_details;
        $studentDataModel->phone = $user->additional->phone;
        $studentDataModel->email = $user->email;

        $changedValues = $studentDataModel->attributes;

        if ($studentDataModel->load(Yii::$app->request->post()) && $studentDataModel->validate()) {

            $user->email = $studentDataModel->email;
            $user->save(false);

            $user->additional->passport_details = $studentDataModel->passport_details;
            $user->additional->phone = $studentDataModel->phone;
            $user->additional->save(false);

            //TODO set flash message data

            Yii::$app->session->setFlash('message',\common\components\I18n::translate('user_data_successfully_updated'));


            ActionLogs::log(
                ActionLogs::ACTION_UPDATE,
                EntityTypes::STUDENT,
                $studentDataModel->id,
                $changedValues,
                $studentDataModel->attributes,
            );

            return $this->redirect('/'.Yii::$app->globalData->lang.'/student/student-personal-dashboard');

        }




        return $this->render('student-personal-dashboard',
            [
                'studentDataModel' => $studentDataModel,
                'user' => $user,
                'faculty' => $faculty,
                'chair' => $chair,
            ],
        );
    }
}
