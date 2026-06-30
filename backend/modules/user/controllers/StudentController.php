<?php

namespace backend\modules\user\controllers;


use backend\modules\user\models\ApplicantForm;
use backend\modules\user\models\StudentForm;
use backend\modules\user\models\StudentSearch;
use common\models\Chairs;
use common\models\Student;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class StudentController extends Controller
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
     * Lists all Student models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StudentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $model = new StudentForm($this->findModel($id)),
        ]);
    }

    /**
     * Updates an existing Student model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new StudentForm($this->findModel($id));
        $model->scenario = StudentForm::SCENARIO_UPDATE;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->updateApplicant()) {
            Yii::$app->session->setFlash('success', 'Student updated.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Student model.
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
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Student
    {
        $model = Student::find()
            ->with(['additional', 'faculty'])
            ->where(['id' => $id])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested student does not exist.');
        }

        return $model;
    }

}
