<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\LibraryModule;

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

}
