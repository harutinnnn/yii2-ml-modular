<?php

namespace backend\modules\user\controllers;

use backend\modules\user\models\ApplicantForm;
use backend\modules\user\models\ApplicantSearch;
use common\models\Applicant;
use common\models\Chairs;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class ApplicantController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Applicant models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ApplicantSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Applicant model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $model = new ApplicantForm($this->findModel($id)),
        ]);
    }

    /**
     * Creates a new Applicant model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ApplicantForm();
        $model->scenario = ApplicantForm::SCENARIO_CREATE;
        $model->chair_id =0;


        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->registerApplicant()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
//            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Applicant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new ApplicantForm($this->findModel($id));
        $model->scenario = ApplicantForm::SCENARIO_UPDATE;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->updateApplicant()) {
            Yii::$app->session->setFlash('success', 'Applicant updated.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Applicant model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return array
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionGetChairs(): array
    {

        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\BadRequestHttpException('Invalid request.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return Chairs::getFalcultiesKeyVal(intval($this->request->get('faculty_id')));
    }

    /**
     * Finds the Applicant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Applicant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Applicant
    {
        $model = Applicant::find()
            ->with(['additional', 'faculty'])
            ->where(['id' => $id])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested applicant does not exist.');
        }

        return $model;
    }

}
