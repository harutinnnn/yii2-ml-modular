<?php

namespace backend\modules\user\controllers;


use backend\modules\user\models\ApplicantForm;
use backend\modules\user\models\TeacherForm;
use backend\modules\user\models\TeacherSearch;
use backend\modules\user\models\User;
use common\components\EntityTypes;
use common\models\ActionLogs;
use common\models\Chairs;
use common\models\Teacher;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class TeacherController extends Controller
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
     * Lists all Teacher models.
     *
     * @return string
     */
    public function actionIndex()
{
$searchModel = new TeacherSearch();
$dataProvider = $searchModel->search($this->request->queryParams);

return $this->render('index', [
'searchModel' => $searchModel,
'dataProvider' => $dataProvider,
]);
}

    /**
     * Displays a single Teacher model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $model = new TeacherForm($this->findModel($id)),
        ]);
    }

    /**
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new TeacherForm();
        $model->scenario = TeacherForm::SCENARIO_CREATE;
        $model->faculty = 0;
        $model->chair = 0;


        if ($this->request->isPost) {

            if ($model->load($this->request->post()) && $model->createTeacher()) {

                ActionLogs::log(
                    ActionLogs::ACTION_CREATE,
                    EntityTypes::TEACHER,
                    $model->id,
                    [],
                    $model->attributes,
                );

                Yii::$app->session->setFlash('success', 'Applicant updated.');
                return $this->redirect(['index']);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Teacher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new TeacherForm($this->findModel($id));
        $model->scenario = TeacherForm::SCENARIO_UPDATE;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->updateTeacher()) {

            ActionLogs::log(
                ActionLogs::ACTION_UPDATE,
                EntityTypes::TEACHER,
                $model->id,
                [],
                $model->attributes,
            );

            Yii::$app->session->setFlash('success', 'Teacher updated.');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Teacher model.
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
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): Teacher
    {
        $model = Teacher::find()
            ->with(['additional',])
            ->where(['id' => $id])
            ->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested Teacher does not exist.');
        }

        return $model;
    }

    public function actionGetChairs(): array
    {

        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\BadRequestHttpException('Invalid request.');
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return Chairs::getFalcultiesKeyVal(intval($this->request->get('faculty_id')));
    }
}
