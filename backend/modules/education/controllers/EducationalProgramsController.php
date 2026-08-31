<?php

namespace backend\modules\education\controllers;

use backend\modules\education\models\EducationalProgramsForm;
use backend\modules\education\models\EducationalProgramsSearch;
use common\models\EducationalPrograms;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EducationalProgramsController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['teacher', 'admin'],
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
        $searchModel = new EducationalProgramsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'levels' => \yii\helpers\ArrayHelper::map(\common\models\EducationLevels::find()->all(), 'id', function ($level) {
                return $level->getDisplayTitle();
            })
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new EducationalProgramsForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Education program created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
            'levels' => \yii\helpers\ArrayHelper::map(\common\models\EducationLevels::find()->all(), 'id', function ($level) {
                return $level->getDisplayTitle();
            })
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'levels' => \yii\helpers\ArrayHelper::map(\common\models\EducationLevels::find()->all(), 'id', function ($level) {
                return $level->getDisplayTitle();
            })
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new EducationalProgramsForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Education program updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
            'levels' => \yii\helpers\ArrayHelper::map(\common\models\EducationLevels::find()->all(), 'id', function ($level) {
                return $level->getDisplayTitle();
            })
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Education program deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): EducationalPrograms
    {
        $model = EducationalPrograms::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested Education program does not exist.');
        }

        return $model;
    }
}
