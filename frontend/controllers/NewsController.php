<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\Announcements;
use common\models\News;
use common\models\NewsCategories;
use common\models\NewsMl;
use common\models\TranslationModel;
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

        Yii::$app->globalData->headerBottomNavbar = '//news/header-bottom-navbar';

        $newsCategories = NewsCategories::find()->all();

        $category = Yii::$app->request->get('category');

        $query = News::find()
            ->innerJoin(NewsMl::tableName(), NewsMl::tableName() . '.news_id = ' . News::tableName() . '.id AND ' . NewsMl::tableName() . '.lang = "' . \Yii::$app->globalData->lang . '"')
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
                'pageSize' => News::PER_PAGE,
                'pageSizeParam' => false,
                'forcePageParam' => false,
                'route' => 'news',
            ],
        ]);


        $announcements = Announcements::find()
            ->where(['status' => 1])
            ->limit(2)
            ->orderBy(['date' => SORT_DESC])->all();


        $menuObj = $this->pageData['menuObj'] ?? null;

        Yii::$app->globalData->setPageData('bread', TranslationModel::getTranslation($menuObj, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('preTitle', \common\components\I18n::translate('structure'));
        Yii::$app->globalData->setPageData('pageTitle', TranslationModel::getTranslation($menuObj, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('afterTitle', TranslationModel::getTranslation($menuObj, Yii::$app->globalData->lang, 'description'));
        Yii::$app->globalData->setPageData('headerImage', $menuObj->header_image ?? '-');


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


        $newsItemCheckOtherLang = News::find()
            ->innerJoin(
                NewsMl::tableName(),
                NewsMl::tableName() . '.news_id = ' . News::tableName() . '.id'
            )
            ->where([NewsMl::tableName() . '.slug' => $slug])
            ->one();


        if (!$newsItemCheckOtherLang) {
            return $this->redirect('/' . Yii::$app->globalData->lang . '/news');
        }


        $newsAllLangList = News::find()->select([NewsMl::tableName() . '.lang', NewsMl::tableName() . '.title', News::tableName() . '.id', NewsMl::tableName() . '.slug'])
            ->innerJoin(
                NewsMl::tableName(),
                NewsMl::tableName() . '.news_id = ' . News::tableName() . '.id'
            )
            ->where([News::tableName() . '.id' => $newsItemCheckOtherLang->id])
            ->createCommand()
            ->queryAll();


        $navLangList = [];
        foreach ($newsAllLangList as $newsAllLang) {
            $navLangList[$newsAllLang['lang']] = '/' . $newsAllLang['lang'] . '/news/' . $newsAllLang['slug'];
        }
        Yii::$app->globalData->customLanguageSLugs = $navLangList;


        Yii::$app->globalData->setPageData('bread', TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('preTitle', \common\components\I18n::translate('news'));
        Yii::$app->globalData->setPageData('pageTitle', TranslationModel::getTranslation($newsItem, Yii::$app->globalData->lang));
        Yii::$app->globalData->setPageData('headerImage', '/images/editorial/campus-entrance.jpg');



        return $this->render('view',
            [
                'newsItem' => $newsItem,
                'newsItemCheckOtherLang' => $newsItemCheckOtherLang,
                'navLangList' => $navLangList
            ]
        );
    }

}
