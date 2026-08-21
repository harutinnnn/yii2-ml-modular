<?php

namespace backend\modules\news\controllers;

use backend\modules\news\models\AnnouncementsForm;
use backend\modules\news\models\AnnouncementsSearch;
use common\models\Announcements;
use common\models\NewsCategories;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class AnnouncementsController extends Controller
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
        $searchModel = new AnnouncementsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories' => ArrayHelper::map(NewsCategories::find()->all(), 'id', function ($category) {
                return $category->translations['en']->title;
            })
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new AnnouncementsForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Announcements created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
            'categories' => ArrayHelper::map(NewsCategories::find()->all(), 'id', function ($category) {
                return $category->translations['en']->title;
            })
        ]);
    }

    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'categories' => ArrayHelper::map(NewsCategories::find()->all(), 'id', function ($category) {
                return $category->translations['en']->title;
            })
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new AnnouncementsForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Announcements updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
            'categories' => ArrayHelper::map(NewsCategories::find()->all(), 'id', function ($category) {
                return $category->translations['en']->title;
            })
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Announcements deleted.');

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

        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/announcements/editorjs';
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
                'url' => '/uploads/announcements/editorjs/' . $fileName,
            ],
        ];
    }

    protected function findModel(int $id): Announcements
    {
        $model = Announcements::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested Announcements does not exist.');
        }

        return $model;
    }
}
