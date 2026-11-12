<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\LibraryItem;
use common\models\LibraryModule;
use mysql_xdevapi\CollectionModify;

/**
 * Site controller
 */
class LibraryController extends MyController
{


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $this->layout = 'library';


        $libraryModules = LibraryModule::find()->all();

        return $this->render('index',
            [
                'libraryModules' => $libraryModules,
            ]);
    }

    public function actionModule($moduleSlug = null)
    {

        $libraryModule = LibraryModule::find()->where(['slug' => $moduleSlug])->one();
        if (empty($libraryModule)) {
            $this->redirect('/' . Yii::$app->globalData->lang . '/digital-library');
        }

        $libraryItems = LibraryItem::find()->where(['library_module_id' => $libraryModule->id])->all();


        return $this->render('module',
            [
                'libraryItems' => $libraryItems
            ]);
    }

}
