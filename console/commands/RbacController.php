<?php

namespace app\commands;

class RbacController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

}
