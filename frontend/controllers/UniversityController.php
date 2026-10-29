<?php

namespace frontend\controllers;


use common\components\I18n;
use common\components\StatusList;
use common\models\Chairs;
use common\models\Faculties;
use common\models\TranslationModel;
use Yii;

/**
 * Site controller
 */
class UniversityController extends MyController
{


    public function actionFaculties()
    {

        $this->layout = 'university';

        $faculties = Faculties::find()->where(['status' => StatusList::STATUS_PUBLISHED])->orderBy(['pos' => SORT_ASC])->all();

        return $this->render('faculties',
            [
                'faculties' => $faculties
            ]
        );
    }

    public function actionView($id)
    {

        $this->layout = 'university';

        $faculty = Faculties::findOne(intval($id));


        $chairs = Chairs::find()->where(['faculty_id' => intval($id)])->orderBy(['pos' => SORT_ASC])->all();

        return $this->render('faculty-view',
            [
                'sideMenuList' => $this->pageData['sideMenuList'],
                'faculty' => $faculty,
                "chairs" => $chairs
            ]
        );
    }

    public function actionChair($id)
    {

        $this->layout = 'university';

        $chair = Chairs::findOne(intval($id));
        Yii::$app->globalData->setPageData('bread', TranslationModel::getTranslation($chair, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('preTitle', I18n::translate('department'));
        Yii::$app->globalData->setPageData('pageTitle', TranslationModel::getTranslation($chair, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('afterTitle', TranslationModel::getTranslation($chair, Yii::$app->globalData->lang, 'text'));
        Yii::$app->globalData->setPageData('headerImage', $chair->image);


        $chairs = Chairs::find()->where(['faculty_id' => intval($id)])->orderBy(['pos' => SORT_ASC])->all();

        return $this->render('chair',
            [
                'sideMenuList' => $this->pageData['sideMenuList'],
                "chair" => $chair,
                "chairs" => $chairs
            ]
        );
    }

}
