<?php

namespace frontend\controllers;

use common\components\EntityTypes;
use common\components\I18n;
use common\components\UserRoles;
use common\models\ActionLogs;
use common\models\ApplicationsAndInquiries;
use common\models\Chairs;
use common\models\Faculties;
use common\models\ScientificPortfolio;
use common\models\User;
use frontend\models\StudentDataForm;
use frontend\models\StudentLoginForm;
use Yii;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class StudentController extends MyController
{

    public function beforeAction($action)
    {

        // Your check
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        } else {

            if ($this->userType != UserRoles::STUDENT) {
                return $this->redirect('/' . Yii::$app->globalData->lang . '/site/login');
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
        $studentDataModel->contact_email = $user->contact_email;

        $changedValues = $studentDataModel->attributes;

        if ($studentDataModel->load(Yii::$app->request->post()) && $studentDataModel->validate()) {

            $user->contact_email = $studentDataModel->contact_email;
            $user->save(false);

            $user->additional->passport_details = $studentDataModel->passport_details;
            $user->additional->phone = $studentDataModel->phone;
            $user->additional->save(false);

            //TODO set flash message data

            Yii::$app->session->setFlash('message', I18n::translate('user_data_successfully_updated'));


            ActionLogs::log(
                ActionLogs::ACTION_UPDATE,
                EntityTypes::STUDENT,
                $studentDataModel->id,
                $changedValues,
                $studentDataModel->attributes,
            );
            return $this->refresh();
        }

        $applicationsAndInquiries = new ApplicationsAndInquiries();
        $applicationsAndInquiries->user_id = $userId;

        if ($applicationsAndInquiries->load(Yii::$app->request->post()) && $applicationsAndInquiries->validate()) {
            $applicationsAndInquiries->created_at = time();
            $applicationsAndInquiries->updated_at = time();
            $applicationsAndInquiries->save();


            Yii::$app->session->setFlash('applications_and_inquiries_message', I18n::translate('applications_and_inquiries_message_sent'));
            return $this->refresh('#requests');
        }

        $scientificPortfolio = new ScientificPortfolio();
        $scientificPortfolio->user_id = $userId;
        if ($scientificPortfolio->load(Yii::$app->request->post()) && $scientificPortfolio->validate()) {

            $scientificPortfolio->created_at = time();
            $scientificPortfolio->updated_at = time();


            $scientificPortfolio->save();

            Yii::$app->session->setFlash('portfolio_message', I18n::translate('the_scientific_work_has_been_added_portfolio'));

            return $this->refresh('#portfolio');
        }


        $applicationsAndInquiriesList = ApplicationsAndInquiries::find()->where(['user_id' => $userId])->all();


        return $this->render('student-personal-dashboard',
            [
                'studentDataModel' => $studentDataModel,
                'user' => $user,
                'faculty' => $faculty,
                'chair' => $chair,
                'applicationsAndInquiries' => $applicationsAndInquiries,
                'applicationsAndInquiriesList' => $applicationsAndInquiriesList,
                'scientificPortfolio' => $scientificPortfolio,
            ],
        );
    }
}
