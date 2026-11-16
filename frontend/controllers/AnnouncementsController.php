<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\Announcements;
use common\models\AnnouncementsMl;
use common\models\News;
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

        $query = Announcements::find()
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


        if (!$announcement) {
            $this->redirect('/' . \Yii::$app->globalData->lang . '/announcements');
        }

        return $this->render('view',
            ['announcement' => $announcement]
        );
    }

}
