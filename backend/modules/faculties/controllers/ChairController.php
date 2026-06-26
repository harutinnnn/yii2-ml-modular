<?php

namespace backend\modules\faculties\controllers;

use backend\modules\faculties\models\ChairsForm;
use backend\modules\faculties\models\ChairsSearch;
use common\models\Chairs;
use common\models\Faculties;
use common\models\Language;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ChairController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
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

    public function actionIndex(): string
    {

        $searchModel = new ChairsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'faculties' => Faculties::getFalcultiesKeyVal()
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new ChairsForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Chair created.');

            return $this->redirect(['index']);
        }




        return $this->render('create', [
            'model' => $form,
            'faculties' => Faculties::getFalcultiesKeyVal()
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'faculties' => Faculties::getFalcultiesKeyVal()
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new ChairsForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Chair updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
            'faculties' => Faculties::getFalcultiesKeyVal()
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Chair deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Chairs
    {
        $model = Chairs::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested chair does not exist.');
        }

        return $model;
    }
}
