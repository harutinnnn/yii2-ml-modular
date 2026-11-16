<?php

namespace frontend\controllers;

use common\components\StatusList;
use common\models\Content;
use common\models\Language;
use common\models\Menu;
use common\models\Section;
use Yii;
use yii\web\Controller;

class MyController extends Controller
{

    public $pageData = [];
    public $lang = 'am';

    public $userType = null;
    public $user = null;


    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->userType = Yii::$app->session->get('userType') ?? null;

        if (!Yii::$app->user->isGuest && !$this->userType) {
            $roles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->id);
            $roles = array_keys($roles);
            $usertype = reset($roles);
            Yii::$app->session->set('userType', $usertype);
        }

        $lang = Yii::$app->request->get('language');
        if (in_array($lang, ['en', 'ru', 'am'])) {
            $this->lang = $lang;
        }

        \Yii::$app->globalData->setLang($this->lang);
        \Yii::$app->globalData->init();


        $mainMenuItems = [];

        $mainSection = Section::find()->where(['key' => 'main-menu'])->one();


        if (!empty($mainSection)) {

            $mainMenu = Menu::find()
                ->where(['parent_id' => null, 'section_id' => $mainSection->id, 'show_in_menu' => StatusList::STATUS_PUBLISHED])
                ->orderBy(['position' => SORT_ASC])
                ->all();


            foreach ($mainMenu as $item) {

                $tmpNodes = [];
                $nodes = Menu::find()
                    ->where(['parent_id' => $item->id])
                    ->orderBy(['position' => SORT_ASC])
                    ->all();


                foreach ($nodes as $node) {

                    $subNodes = Menu::find()
                        ->where(['parent_id' => $node->id])
                        ->orderBy(['position' => SORT_ASC])
                        ->all();

                    $tmpSubNodes = [];
                    foreach ($subNodes as $subNode) {
                        $tmpSubNodes[$subNode->id] = [
                            'menu' => $subNode,
                            'nodes' => null
                        ];
                    }

                    $tmpNodes[$node->id] = [
                        'menu' => $node,
                        'nodes' => $tmpSubNodes
                    ];
                }


                $mainMenuItems[$item->id] = [
                    'menu' => $item,
                    'is_flat_menu' => $item->is_flat_menu,
                    'nodes' => $tmpNodes
                ];

            }
        }

        $this->pageData['mainMenuItems'] = $mainMenuItems;


        $urlParts = explode('/', Yii::$app->request->pathInfo);

        $currentUrl = $urlParts[2] ?? $urlParts[1] ?? $urlParts[0] ?? '/';
        if (in_array($currentUrl, ['en', 'ru', 'am'])) {
            $currentUrl = '/';
        }


        $preParentMenuObj = null;
        $parentMenuObj = null;
        $menuObj = Menu::find()->where(['url' => $currentUrl])->one();

        $content = null;


        if (isset($menuObj->parent_id) && $menuObj->parent_id) {
            $parentMenuObj = Menu::find()->where(['id' => $menuObj->parent_id])->one() ?? null;
            $content = Content::find()->where(['id' => $menuObj->content_id])->one() ?? null;
        }

        if (isset($parentMenuObj->parent_id) && $parentMenuObj->parent_id) {
            $preParentMenuObj = Menu::find()->where(['id' => $parentMenuObj->parent_id])->one() ?? null;
        }


        $sideMenuList = [];


        if (isset($menuObj->sideMenu) && !empty($menuObj->sideMenu)) {
            foreach ($menuObj->sideMenu as $sideMenuObj) {

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



        $this->pageData['sideMenuList'] = $sideMenuList;

        $this->pageData['menuObj'] = $menuObj;
        $this->pageData['parentMenuObj'] = $parentMenuObj;
        $this->pageData['preParentMenuObj'] = $preParentMenuObj;

        $this->pageData['content'] = $content;


    }

}