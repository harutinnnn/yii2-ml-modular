<?php

namespace backend\modules\news\controllers;

use backend\modules\news\models\NewsCategoriesForm;
use backend\modules\news\models\NewsCategoriesSearch;
use common\models\NewsCategories;
use Yii;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class NewsCategoriesController extends Controller
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
        $searchModel = new NewsCategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new NewsCategoriesForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'News category created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new NewsCategoriesForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'News category updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'News category deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): NewsCategories
    {
        $model = NewsCategories::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested News category does not exist.');
        }

        return $model;
    }
}
