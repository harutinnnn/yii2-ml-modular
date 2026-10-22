<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\Announcements;
use common\models\News;
use common\models\NewsCategories;
use common\models\NewsMl;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class NewsController extends MyController
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $newsCategories = NewsCategories::find()->all();

        $category = Yii::$app->request->get('category');

        $query = News::find()
            ->where(['status' => 1])
            ->orderBy(['id' => SORT_DESC]);
        if (intval($category)) {
            $query->andWhere(['category_id' => $category]);
        }

        $latestNews = [];
        if ($query->count() > News::PER_PAGE) {
            $latestNews = News::find()->limit(3)->orderBy(['date' => SORT_DESC])->all();
            $latestNewsIds = ArrayHelper::getColumn($latestNews, "id");
            if (!empty($latestNewsIds)) {
                $query->andWhere(['not in', 'id', $latestNewsIds]);
            }

        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
//                'pageSize' => News::PER_PAGE,
                'pageSize' => 1,
                'pageSizeParam' => false,
                'forcePageParam' => false,
                'route' => 'news',
            ],
        ]);


        $announcements = Announcements::find()
            ->where(['status' => 1])
            ->limit(2)
            ->orderBy(['date' => SORT_DESC])->all();


        return $this->render('index', [
            'newsCategories' => $newsCategories,
            'news' => $dataProvider->getModels(),
            'pagination' => $dataProvider->pagination,
            'announcements' => $announcements,
            'latestNews' => $latestNews
        ]);
    }

    public function actionView($slug)
    {

        $newsItem = News::find()
            ->innerJoin(
                NewsMl::tableName() . ' neMl', 'neMl.news_id = ' . News::tableName() . '.id AND lang = "' . \Yii::$app->globalData->lang . '"'
            )
            ->where(['slug' => $slug])
            ->one();


        if (!$newsItem) {
            $this->redirect('/' . \Yii::$app->globalData->lang . '/announcements');
        }

        return $this->render('view',
            ['newsItem' => $newsItem]
        );
    }

}
