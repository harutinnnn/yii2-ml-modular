<?php

namespace frontend\controllers;

use backend\modules\user\models\ApplicantForm;
use common\helpers\I18n;
use common\models\Chairs;
use common\models\EducationLevels;
use common\models\Faculties;
use common\models\Menu;
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


        return $this->render('online-application',
            [
                'education_levels' => ArrayHelper::map(EducationLevels::find()->all(),
                    'id',
                    function ($educationLevel) {
                        return $educationLevel->getTranslation(Yii::$app->globalData->lang)->title;
                    }),
            ]);
    }
}
