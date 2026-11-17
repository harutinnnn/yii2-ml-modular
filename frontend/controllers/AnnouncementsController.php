<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\Announcements;
use common\models\AnnouncementsMl;
use common\models\News;
use common\models\TranslationModel;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class AnnouncementsController extends MyController
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->globalData->headerBottomNavbar = '//news/header-bottom-navbar';

        $query = Announcements::find()
            ->innerJoin(AnnouncementsMl::tableName(), AnnouncementsMl::tableName() . '.announcements_id = ' . Announcements::tableName() . '.id AND ' . AnnouncementsMl::tableName() . '.lang = "' . \Yii::$app->globalData->lang . '"')
            ->where(['status' => 1])
            ->orderBy(['id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Announcements::PER_PAGE,
                'pageSizeParam' => false,
                'forcePageParam' => false,
                'route' => 'announcements',
            ],
        ]);


        $latestNews = News::find()->limit(3)->orderBy(['date' => SORT_DESC])->all();

        return $this->render('index',
            [
                'announcements' => $dataProvider->getModels(),
                'pagination' => $dataProvider->pagination,
                'latestNews' => $latestNews
            ]
        );
    }

    public function actionView($slug)
    {

        $announcement = Announcements::find()
            ->innerJoin(
                AnnouncementsMl::tableName() . ' anMl', 'anMl.announcements_id = ' . Announcements::tableName() . '.id AND lang = "' . \Yii::$app->globalData->lang . '"'
            )
            ->where(['slug' => $slug])
            ->one();


        $announcementsItemCheckOtherLang = Announcements::find()
            ->innerJoin(
                AnnouncementsMl::tableName(),
                AnnouncementsMl::tableName() . '.announcements_id = ' . Announcements::tableName() . '.id'
            )
            ->where([AnnouncementsMl::tableName() . '.slug' => $slug])
            ->one();

        if (!$announcementsItemCheckOtherLang) {
            $this->redirect('/' . \Yii::$app->globalData->lang . '/announcements');
        }


        $announcementsAllLangList = Announcements::find()->select([AnnouncementsMl::tableName() . '.lang', AnnouncementsMl::tableName() . '.title', Announcements::tableName() . '.id', AnnouncementsMl::tableName() . '.slug'])
            ->innerJoin(
                AnnouncementsMl::tableName(),
                AnnouncementsMl::tableName() . '.announcements_id = ' . Announcements::tableName() . '.id'
            )
            ->where([Announcements::tableName() . '.id' => $announcementsItemCheckOtherLang->id])
            ->createCommand()
            ->queryAll();

        $navLangList = [];
        foreach ($announcementsAllLangList as $announcementsAllLang) {
            $navLangList[$announcementsAllLang['lang']] = '/' . $announcementsAllLang['lang'] . '/announcements/' . $announcementsAllLang['slug'];
        }
        Yii::$app->globalData->customLanguageSLugs = $navLangList;


        Yii::$app->globalData->setPageData('bread', TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('preTitle', \common\components\I18n::translate('announcements'));
        Yii::$app->globalData->setPageData('pageTitle', TranslationModel::getTranslation($announcement, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('headerImage', '/images/editorial/campus-entrance.jpg');

        return $this->render('view',
            [
                'announcement' => $announcement,
                'announcementsItemCheckOtherLang' => $announcementsItemCheckOtherLang,
                'navLangList' => $navLangList
            ]
        );
    }

}
