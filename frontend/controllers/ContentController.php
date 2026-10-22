<?php

namespace frontend\controllers;

use common\helpers\I18n;
use common\models\EducationalPrograms;
use common\models\EducationLevels;
use common\models\EducationPlan;
use common\models\Language;
use common\models\Menu;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class ContentController extends MyController
{

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($slug1, $slug2)
    {


        $sideMenuList = [];

        if (isset($this->pageData['menuObj']->sideMenu) && !empty($this->pageData['menuObj']->sideMenu)) {
            foreach ($this->pageData['menuObj']->sideMenu as $sideMenuObj) {

                $sideMenu = $sideMenuObj?->sideMenu;
                $parentSideMenu = null;
                $preParentSideMenu = null;

                if (isset($sideMenu->parent_id) && $sideMenu->parent_id) {

                    $parentSideMenu = $sideMenu->parent;
                    if (isset($parentSideMenu->parent_id) && $parentSideMenu->parent_id) {
                        $preParentSideMenu = $parentSideMenu->parent;
                    }

                }

                $url = '/' . Yii::$app->globalData->lang . '/';

                if (isset($preParentSideMenu->url) && strlen(trim($preParentSideMenu->url))) {
                    $url .= $preParentSideMenu->url . '/';
                } else if (isset($parentSideMenu->url) && strlen(trim($parentSideMenu->url))) {
                    $url .= $parentSideMenu->url . '/';
                }

                $url .= $sideMenu->url;

                $sideMenuList[] = [
                    'title' => $sideMenu?->getTranslation(Yii::$app->globalData->lang)->title,
                    'url' => $url,
                ];
            }
        }

        return $this->render('index',
            [
                'sideMenuList' => $sideMenuList
            ]
        );
    }

}
