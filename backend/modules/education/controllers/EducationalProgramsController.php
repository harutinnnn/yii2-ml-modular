<?php

namespace backend\modules\education\controllers;

use backend\modules\education\models\EducationalProgramsForm;
use backend\modules\education\models\EducationalProgramsSearch;
use backend\modules\education\models\EducationPlanForm;
use backend\modules\education\models\EducationPlanSearch;
use common\models\EducationalPrograms;
use common\models\EducationPlan;
use common\models\Language;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
            }),
            'langList' => ArrayHelper::map(Language::find()->all(), 'code', 'code')
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
            }),
            'langList' => ArrayHelper::map(Language::find()->all(), 'code', 'code')
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


    public function actionPlans(int $programId): string
    {
        $searchModel = new EducationPlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $programId);

        return $this->render('plans', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'programId' => $programId
        ]);
    }

    public function actionPlansCreate(int $programId): string|Response
    {
        $form = new EducationPlanForm();

        if ($form->load(Yii::$app->request->post()) && $form->save($programId)) {
            Yii::$app->session->setFlash('success', 'Education program created.');

            return $this->redirect(['plans', 'programId' => $programId]);
        }

        return $this->render('plans-create', [
            'model' => $form,
            'programId' => $programId
        ]);
    }

    public function actionPlansUpdate(int $id, int $programId): string|Response
    {
        $form = new EducationPlanForm($this->findPlanModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save($programId)) {
            Yii::$app->session->setFlash('success', 'Education program updated.');

            return $this->redirect(['plans', 'programId' => $programId]);
        }

        return $this->render('plans-update', [
            'model' => $form,
            'programId' => $programId
        ]);
    }

    public function actionPlansDelete(int $id, int $programId): Response
    {
        $this->findPlanModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Education program deleted.');

        return $this->redirect(['plans', 'programId' => $programId]);
    }

    protected function findPlanModel(int $id): EducationPlan
    {
        $model = EducationPlan::find()->with('translations')->where(['id' => $id])->one();


        if ($model === null) {
            throw new NotFoundHttpException('The requested Education program does not exist.');
        }

        return $model;
    }

}
