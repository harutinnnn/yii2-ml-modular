<?php

namespace backend\modules\news\controllers;

use backend\modules\news\models\NewsTagsForm;
use backend\modules\news\models\NewsTagsSearch;
use common\models\NewsTags;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class NewsTagsController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['teacher','admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'upload-image' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new NewsTagsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new NewsTagsForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'News tag created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new NewsTagsForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'News tag updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'News tag deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): NewsTags
    {
        $model = NewsTags::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested News tag does not exist.');
        }

        return $model;
    }
}
