<?php

namespace frontend\controllers;

use yii\web\Controller;

class MyController extends Controller
{

    public $pageData = [];
    public $lang = 'am';


    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        \Yii::$app->globalData->setLang($this->lang);
        \Yii::$app->globalData->init();


    }

}