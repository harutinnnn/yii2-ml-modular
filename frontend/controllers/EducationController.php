<?php

namespace frontend\controllers;

use backend\modules\user\models\ApplicantForm;
use common\helpers\I18n;
use common\models\Admissions;
use common\models\Chairs;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\EducationPlan;
use common\models\Faculties;
use common\models\Language;
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
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class EducationController extends MyController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionAllPrograms()
    {


        $educationLevels = EducationLevels::find()->where(['as_edu_level' => 1])->orderBy(['pos' => SORT_ASC])->all();
        $mIds = ArrayHelper::getColumn($educationLevels, 'm_id');
        $levelAttachedMenus = [];

        if (!empty($mIds)) {
            $levelAttachedMenus = ArrayHelper::map(Menu::find()->where(['in', 'id', $mIds])->all(), 'id', function ($model) {
                return $model;
            });
        }

        $continuingEducationPrograms = EducationalPrograms::find()->where(['continuing_education' => 1])->limit(3)->all();

        return $this->render('all-programs',
            [
                'educationLevels' => ArrayHelper::map($educationLevels, 'id', static fn($model) => $model),
                'levelAttachedMenus' => $levelAttachedMenus,
                'continuingEducationPrograms' => $continuingEducationPrograms
            ]);
    }


    public function actionBachelorsPrograms()
    {
        $langs = ArrayHelper::map(Language::find()->all(), 'code', 'name');

        $programs = [];

        $menuId = $this->pageData['menuObj']->id ?? null;

        $level = EducationLevels::find()->where(['m_id' => $menuId])->one();
        if (isset($level->id)) {
            $programs = EducationalPrograms::find()->where(['education_level' => $level->id, 'continuing_education' => 0])->all();
        }

        return $this->render('bachelors-programs',
            [
                'programs' => $programs,
                'level' => $level,
                'langs' => $langs
            ]
        );
    }
    public function actionMastersPrograms()
    {
        $langs = ArrayHelper::map(Language::find()->all(), 'code', 'name');

        $programs = [];

        $menuId = $this->pageData['menuObj']->id ?? null;

        $level = EducationLevels::find()->where(['m_id' => $menuId])->one();
        if (isset($level->id)) {
            $programs = EducationalPrograms::find()->where(['education_level' => $level->id, 'continuing_education' => 0])->all();
        }

        return $this->render('bachelors-programs',
            [
                'programs' => $programs,
                'level' => $level,
                'langs' => $langs
            ]
        );
    }
    public function actionPhdPrograms()
    {
        $langs = ArrayHelper::map(Language::find()->all(), 'code', 'name');

        $programs = [];

        $menuId = $this->pageData['menuObj']->id ?? null;

        $level = EducationLevels::find()->where(['m_id' => $menuId])->one();
        if (isset($level->id)) {
            $programs = EducationalPrograms::find()->where(['education_level' => $level->id, 'continuing_education' => 0])->all();
        }

        return $this->render('bachelors-programs',
            [
                'programs' => $programs,
                'level' => $level,
                'langs' => $langs
            ]
        );
    }

    public function actionProgram(int $id, $controller)
    {

        $program = EducationalPrograms::findOne($id);
        if (empty($program)) {
            throw new  NotFoundHttpException();
        }
        $plans = EducationPlan::find()->where(['educational_program_id' => $id])->orderBy(['pos' => SORT_ASC])->all();


        $this->pageData['menuObj'] = $program;

        $this->pageData['plans'] = $plans;

        $this->view->params['headerImage'] = $program->getTranslation(Yii::$app->globalData->lang)->img;

        return $this->render('program',
            [
                'program' => $program,
                'plans' => $plans,
                'level' => EducationLevels::find()->where(['id' => $program->education_level])->one()
            ]
        );
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



    public function actionTeachers()
    {


        return $this->render('teachers',
            [
            ]
        );
    }



}
