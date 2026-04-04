<?php

namespace backend\modules\section\controllers;

use backend\modules\section\models\SectionSearch;
use common\models\Section;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SectionController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [[
                    'allow' => true,
                    'roles' => ['@'],
                ]],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $searchModel = new SectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', compact('searchModel', 'dataProvider'));
    }

    public function actionCreate(): string|Response
    {
        $model = new Section();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Section created.');
            return $this->redirect(['index']);
        }

        return $this->render('create', compact('model'));
    }

    public function actionView(int $id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Section updated.');
            return $this->redirect(['index']);
        }

        return $this->render('update', compact('model'));
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Section deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Section
    {
        $model = Section::findOne($id);
        if ($model === null) {
            throw new NotFoundHttpException('The requested section does not exist.');
        }

        return $model;
    }
}
