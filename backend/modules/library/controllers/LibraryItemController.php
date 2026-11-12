<?php
namespace backend\modules\library\controllers;

use backend\modules\library\models\LibraryItemForm;
use backend\modules\library\models\LibraryItemSearch;
use common\models\LibraryItem;
use common\models\LibraryModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LibraryItemController extends Controller
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
        $searchModel = new LibraryItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'libraryModules' => LibraryModule::getModulesKeyVal()
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new LibraryItemForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Education Level created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
            'libraryModules' => LibraryModule::getModulesKeyVal()
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new LibraryItemForm($this->findModel($id));


        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Education Level updated.');

            return $this->redirect(['index']);
        }


        return $this->render('update', [
            'model' => $form,
            'libraryModules' => LibraryModule::getModulesKeyVal()
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Education Level deleted.');

        return $this->redirect(['index']);
    }

    protected function findModel(int $id): LibraryItem
    {
        $model = LibraryItem::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested Education Level does not exist.');
        }

        return $model;
    }
}
