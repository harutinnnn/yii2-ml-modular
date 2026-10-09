<?php

namespace frontend\controllers;

use common\components\EntityTypes;
use common\components\I18n;
use common\components\StatusList;
use common\components\UserRoles;
use common\models\ActionLogs;
use common\models\ArticlesAndBooksRequests;
use common\models\Chairs;
use common\models\Faculties;
use common\models\User;
use common\models\UserAdditionalData;
use frontend\models\TeacherDataForm;
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

            if ($this->userType != UserRoles::TEACHER) {
                return $this->redirect('/' . Yii::$app->globalData->lang . '/login');
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

        $user = User::findOne($userId);
        $additional = UserAdditionalData::find()->where(['user_id' => $userId])->one();

        if (!$user) {
            return $this->redirect(['/site/login']);
        }


        $teacherDataForm = new TeacherDataForm();

        $teacherDataForm->id = $user->id;
        $teacherDataForm->first_name = $user->additional->first_name;
        $teacherDataForm->last_name = $user->additional->last_name;
        $teacherDataForm->middle_name = $user->additional->middle_name;
        $teacherDataForm->dob = $user->additional->dob;
        $teacherDataForm->passport_details = $user->additional->passport_details;
        $teacherDataForm->phone = $user->additional->phone;
        $teacherDataForm->email = $user->email;
        $teacherDataForm->contact_email = $user->contact_email;
        $teacherDataForm->teacher_academic_degree = $user->additional->teacher_academic_degree;
        $teacherDataForm->teacher_position = $user->additional->teacher_position;

        $teacherDataForm->teacher_language_proficiency = $user->additional->teacher_language_proficiency;
        $teacherDataForm->teacher_work_experience = $user->additional->teacher_work_experience;
        $teacherDataForm->teacher_certificates = $user->additional->teacher_certificates;
        $teacherDataForm->teacher_subjects_taught = $user->additional->teacher_subjects_taught;

        $changedValues = $teacherDataForm->attributes;


        if ($teacherDataForm->load(Yii::$app->request->post()) && $teacherDataForm->validate()) {

            //Update user dates
            $user->contact_email = $teacherDataForm->contact_email;
            $user->save(false);

            //Update user additional dates
            $user->additional->phone = $teacherDataForm->phone;
            $user->additional->teacher_language_proficiency = $teacherDataForm->teacher_language_proficiency;
            $user->additional->teacher_work_experience = $teacherDataForm->teacher_work_experience;
            $user->additional->teacher_certificates = $teacherDataForm->teacher_certificates;
            $user->additional->teacher_subjects_taught = $teacherDataForm->teacher_subjects_taught;
            $user->additional->save(false);

            Yii::$app->session->setFlash('message', I18n::translate('teacher_data_successfully_updated'));

            ActionLogs::log(
                ActionLogs::ACTION_UPDATE,
                EntityTypes::TEACHER,
                $teacherDataForm->id,
                $changedValues,
                $teacherDataForm->attributes,
            );

            return $this->refresh('#persona-data');
        }


        $articlesAndBooksRequests = new ArticlesAndBooksRequests();
        $articlesAndBooksRequests->publication_year = date('Y');
        $articlesAndBooksRequests->user_id = $userId;

        if (Yii::$app->request->get('request_id')) {
            $articlesAndBooksRequests = ArticlesAndBooksRequests::find()->where(['id' => intval(Yii::$app->request->get('request_id')), 'user_id' => $userId])->one();

            if ($articlesAndBooksRequests && $articlesAndBooksRequests->status == StatusList::STATUS_ACTIVE) {
                return $this->redirect('/' . Yii::$app->globalData->lang . '/lecturer/personal-dashboard');
            }

        }

        if ($articlesAndBooksRequests->load(Yii::$app->request->post()) && $articlesAndBooksRequests->validate()) {

            $articlesAndBooksRequests->lang = Yii::$app->globalData->lang;
            $articlesAndBooksRequests->created_at = time();
            $articlesAndBooksRequests->updated_at = time();

            if ($articlesAndBooksRequests->save()) {
                Yii::$app->session->setFlash('article_request_message', I18n::translate('article_request_successfully_sent'));

                if (Yii::$app->request->get('request_id')) {

                    return $this->redirect('/' . Yii::$app->globalData->lang . '/lecturer/personal-dashboard' . '#articles-and-books-requests-'.Yii::$app->request->get('request_id'));
                } else {

                    return $this->refresh('/' . Yii::$app->globalData->lang . '/lecturer/personal-dashboard' . '#research');
                }

            }
        }


        $faculty = Faculties::findOne($user->additional->faculty);
        $chair = Chairs::findOne($user->additional->chair);


        $articlesAndBooksRequestsList = ArticlesAndBooksRequests::find()->where(['user_id' => $userId])->all();

        return $this->render('personal-dashboard',
            [
                'user' => $user,
                'faculty' => $faculty,
                'chair' => $chair,
                'teacherDataForm' => $teacherDataForm,
                'articlesAndBooksRequests' => $articlesAndBooksRequests,
                'articlesAndBooksRequestsList' => $articlesAndBooksRequestsList
            ],
        );
    }
}
