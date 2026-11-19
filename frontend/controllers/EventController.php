<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\Events;
use common\models\EventsMl;
use common\models\News;
use common\models\TranslationModel;
use frontend\models\EventUserRegistration;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

/**
 * Site controller
 */
class EventController extends MyController
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        Yii::$app->globalData->headerBottomNavbar = '//event/header-bottom-navbar';


        $date = Yii::$app->request->get('date');


        $query = Events::find()
            ->innerJoin(EventsMl::tableName(), EventsMl::tableName() . '.events_id = ' . Events::tableName() . '.id AND ' . EventsMl::tableName() . '.lang = "' . \Yii::$app->globalData->lang . '"')
            ->where(['status' => 1])
            ->orderBy(['id' => SORT_DESC]);

        if ($date) {
            $query->andWhere(['date' => $date]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Events::PER_PAGE,
                'pageSizeParam' => false,
                'forcePageParam' => false,
                'route' => 'events',
            ],
        ]);


        $latestNews = News::find()->limit(3)->orderBy(['date' => SORT_DESC])->all();

        return $this->render('index',
            [
                'events' => $dataProvider->getModels(),
                'pagination' => $dataProvider->pagination,
                'latestNews' => $latestNews,
                'date' => $date
            ]
        );
    }

    public function actionView($id)
    {

        $eventItem = Events::find()
            ->innerJoin(
                EventsMl::tableName() . ' anMl', 'anMl.events_id = ' . Events::tableName() . '.id AND lang = "' . \Yii::$app->globalData->lang . '"'
            )
            ->where([Events::tableName() . '.id' => intval($id)])
            ->one();


        $eventsAllLangList = Events::find()->select([EventsMl::tableName() . '.lang', EventsMl::tableName() . '.title', Events::tableName() . '.id'])
            ->innerJoin(
                EventsMl::tableName(),
                EventsMl::tableName() . '.events_id = ' . Events::tableName() . '.id'
            )
            ->where([Events::tableName() . '.id' => intval($id)])
            ->createCommand()
            ->queryAll();


        $navLangList = [];
        foreach ($eventsAllLangList as $eventsAllLang) {
            $navLangList[$eventsAllLang['lang']] = '/' . $eventsAllLang['lang'] . '/events/view/' . $eventsAllLang['id'];
        }
        Yii::$app->globalData->customLanguageSLugs = $navLangList;


        Yii::$app->globalData->setPageData('bread', TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('preTitle', \common\components\I18n::translate('events'));
        Yii::$app->globalData->setPageData('pageTitle', TranslationModel::getTranslation($eventItem, Yii::$app->globalData->lang));

        if (isset($eventItem->image)) {

            Yii::$app->globalData->setPageData('headerImage', $eventItem->image);

        } else {
            Yii::$app->globalData->setPageData('headerImage', '/images/editorial/campus-entrance.jpg');
        }

        return $this->render('view',
            [
                'eventItem' => $eventItem,
                'navLangList' => $navLangList
            ]
        );
    }


    public function actionRegistration($eventId)
    {

        $event = Events::findOne(intval($eventId));

        if (empty($event)) {
            return $this->redirect('/' . Yii::$app->globalData->lang . '/events');
        }

        $model = new EventUserRegistration();
        $model->event_id = intval($eventId);


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {

                Yii::$app->session->setFlash('success_registered');
                return $this->redirect('/' . Yii::$app->globalData->lang . '/events/registration/' . $eventId);

            }
        }
//        dd($model->errors);


        return $this->render('registration',
            [
                'event' => $event,
                'model' => $model,
            ]
        );

    }

    public function actionGetCalendar($year, $month)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        $start = sprintf('%04d-%02d-01', $year, $month);
        $end = date('Y-m-d', strtotime($start . ' +1 month'));

        $rows = Events::find()
            ->select(['date'])
            ->where(['>=', 'date', $start])
            ->andWhere(['<', 'date', $end])
            ->asArray()
            ->all();


        $days = [];
        foreach ($rows as $row) {
            $day = substr($row['date'], 0, 10);
            $days[] = [
                'url' => '/' . \Yii::$app->globalData->lang . '/events?date=' . substr($row['date'], 0, 10),
                'day' => $day,
                'color' => '#7f1424',
                'textColor' => '#ffffff',
            ];
        }

        return $days;

    }


}
