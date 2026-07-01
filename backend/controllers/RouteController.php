<?php

namespace backend\controllers;

use common\components\UserRoles;
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
                    'roles' => [UserRoles::SUPER_ADMIN],
                ],
            ],
        ];

        return $behaviors;
    }
}