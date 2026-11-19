<?php

namespace backend\modules\news\controllers;

use backend\modules\news\models\EventMemberRegistrationsSearch;
use backend\modules\news\models\EventsForm;
use backend\modules\news\models\EventsSearch;
use common\models\EventMemberRegistrations;
use common\models\Events;
use Yii;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class EventsController extends Controller
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
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new EventsForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Events created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new EventsForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Events updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Events deleted.');

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
        if (!in_array(strtolower((string)$file->extension), $allowedExtensions, true)) {
            return [
                'success' => 0,
                'message' => 'Unsupported image format.',
            ];
        }

        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/events/editorjs';
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
                'url' => '/uploads/events/editorjs/' . $fileName,
            ],
        ];
    }

    protected function findModel(int $id): Events
    {
        $model = Events::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested Events does not exist.');
        }

        return $model;
    }

    public function actionApplicants($id): string
    {

        $searchModel = new EventMemberRegistrationsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        return $this->render('applicants', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id
        ]);
    }

    public function actionDelete_applicant($id, $event_id)
    {
        EventMemberRegistrations::findOne([
            'event_id' => $event_id, 'id' => $id])->delete();
        Yii::$app->session->setFlash('success', 'Events applicant deleted.');
        return $this->redirect(['applicants','id' => $event_id]);
    }


    public function actionUpdate_applicant($id, $event_id)
    {
        $model = EventMemberRegistrations::findOne([
            'event_id' => $event_id, 'id' => $id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Events updated.');

            return $this->redirect(['applicants','id' => $event_id]);
        }


        return $this->render('update_applicant', [
            'model' => $model,
        ]);



    }
}
