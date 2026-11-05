<?php

namespace frontend\controllers;


use common\components\I18n;
use common\components\StatusList;
use common\models\ChairEducationProgrammLcp;
use common\models\Chairs;
use common\models\Faculties;
use common\models\TranslationModel;
use common\models\User;
use Yii;

/**
 * Site controller
 */
class ProfileController extends MyController
{


    public function actionIndex($type, $id)
    {


        $user = User::findOne($id);

        if (empty($user)) {
            return $this->goHome();
        }
        $additional = $user->additional ?? null;

        $chair = Chairs::find()
            ->innerJoin('chair_staff_lcp chsl', 'chsl.chair_id = chairs.id')
            ->where(['user_id' => $id])
            ->one();

        $name = ($additional->first_name ?? '') . ' ' . ($additional->last_name ?? '');
        $afterTitle = $additional->teacher_academic_degree;
        if (TranslationModel::getTranslation($chair, Yii::$app->globalData->lang)) {
            $afterTitle .= ' · ' . TranslationModel::getTranslation($chair, Yii::$app->globalData->lang);
        }

        if ($additional->profession && strlen(trim($additional->profession))) {
            $afterTitle .= ' · ' . $additional->profession;
        }

        Yii::$app->globalData->setPageData('bread', $name);
        Yii::$app->globalData->setPageData('preTitle', I18n::translate('lecturer_profile'));
        Yii::$app->globalData->setPageData('pageTitle', $name);
        Yii::$app->globalData->setPageData('afterTitle', $afterTitle);
        Yii::$app->globalData->setPageData('headerImage', '/images/editorial/campus-facade.jpg');


        return $this->render('index',
            [
                'user' => $user,
                'additional' => $additional,
                'chair' => $chair
            ]
        );
    }
}
