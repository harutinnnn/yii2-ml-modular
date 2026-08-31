<?php

namespace frontend\controllers;

use common\models\Content;
use common\models\Menu;
use common\models\Section;
use Yii;
use yii\web\Controller;

class MyController extends Controller
{

    public $pageData = [];
    public $lang = 'am';


    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

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
                ->where(['parent_id' => null, 'section_id' => $mainSection->id])
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
        $this->pageData['menuObj'] = $menuObj;
        $this->pageData['parentMenuObj'] = $parentMenuObj;
        $this->pageData['preParentMenuObj'] = $preParentMenuObj;

        $this->pageData['content'] = $content;



    }

}