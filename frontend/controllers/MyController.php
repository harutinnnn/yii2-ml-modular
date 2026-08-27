<?php

namespace frontend\controllers;

use common\models\Menu;
use common\models\Section;
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
                    foreach ($subNodes as $subNode){
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

    }

}