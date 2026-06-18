<?php

namespace backend\controllers;

use mdm\admin\controllers\RouteController as BaseRouteController;
use yii\filters\AccessControl;

class RouteController extends BaseRouteController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ],
        ];

        return $behaviors;
    }
}