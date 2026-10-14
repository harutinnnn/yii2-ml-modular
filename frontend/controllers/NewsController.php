<?php

namespace frontend\controllers;

use common\helpers\I18n;

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

        return $this->render('index');
    }

}
