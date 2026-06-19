<?php

namespace backend\modules\statuses\controllers;

use backend\modules\statuses\models\StatusesForm;
use backend\modules\statuses\models\StatusesSearch;
use common\models\Statuses;
use Yii;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class StatusController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
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
        $searchModel = new StatusesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new StatusesForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Status created.');

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
        $form = new StatusesForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Status updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Status deleted.');

        return $this->redirect(['index']);
    }

    public function actionUploadImage(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $file = UploadedFile::getInstanceByName('image');
        if ($file === null) {
            return [
                'success' => 0,
                'message' => 'Image file is required.',
            ];
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        if (!in_array(strtolower((string) $file->extension), $allowedExtensions, true)) {
            return [
                'success' => 0,
                'message' => 'Unsupported image format.',
            ];
        }

        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/statuses/editorjs';
        FileHelper::createDirectory($basePath);

        $fileName = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $filePath = $basePath . '/' . $fileName;

        if (!$file->saveAs($filePath)) {
            return [
                'success' => 0,
                'message' => 'Failed to save uploaded image.',
            ];
        }

        return [
            'success' => 1,
            'file' => [
                'url' => '/uploads/statuses/editorjs/' . $fileName,
            ],
        ];
    }

    protected function findModel(int $id): Statuses
    {
        $model = Statuses::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested statuses does not exist.');
        }

        return $model;
    }
}
