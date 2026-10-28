<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\PageBlockForm;
use backend\modules\content\models\PageBlockItemForm;
use backend\modules\content\models\PageBlockItemSearch;
use backend\modules\content\models\PageBlockSearch;
use common\models\PageBlock;
use common\models\PageBlockItem;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PageBlockItemController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'moderator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex($page_block_id = 0): string
    {
        $searchModel = new PageBlockItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, intval($page_block_id));

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page_block_id' => $page_block_id
        ]);
    }

    public function actionCreate($page_block_id = 0): string|Response
    {
        $form = new PageBlockItemForm();

        if ($form->load(Yii::$app->request->post()) && $form->save($page_block_id)) {
            Yii::$app->session->setFlash('success', 'Content created.');

            return $this->redirect(['index', 'page_block_id' => $page_block_id]);
        }

        return $this->render('create', [
            'model' => $form,
            'page_block_id' => $page_block_id
        ]);
    }


    public function actionUpdate(int $id, $page_block_id = 0): string|Response
    {
        $form = new PageBlockItemForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save($page_block_id)) {
            Yii::$app->session->setFlash('success', 'Content updated.');

            return $this->redirect(['index', 'page_block_id' => $page_block_id]);
        }

        return $this->render('update', [
            'model' => $form,
            'page_block_id' => $page_block_id
        ]);
    }


    public function actionDelete(int $id, $page_block_id = 0): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Content deleted.');

        return $this->redirect(['index', 'page_block_id' => $page_block_id]);
    }

    protected function findModel(int $id): PageBlockItem
    {
        $model = PageBlockItem::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested content does not exist.');
        }

        return $model;
    }
}
