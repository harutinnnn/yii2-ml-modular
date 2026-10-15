<?php

namespace frontend\controllers;

use common\helpers\I18n;

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

        return $this->render('index');
    }

}
