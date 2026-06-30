<?php

namespace backend\modules\journal\controllers;

use backend\modules\journal\models\JournalArticleForm;
use backend\modules\journal\models\JournalArticlesSearch;
use backend\modules\journal\models\JournalForm;
use common\models\Journal;
use backend\modules\journal\models\JournalSearch;
use common\models\JournalArticles;
use common\models\JournalAuthors;
use common\models\JournalAuthorsLcp;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * JournalController implements the CRUD actions for Journal model.
 */
class JournalController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
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
                    'upload-image' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Journal models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JournalSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'authors' => ArrayHelper::map(JournalAuthors::find()->all(), 'id', function ($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
        ]);
    }

    /**
     * Displays a single Journal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $journalAuthors = JournalAuthors::find()->alias('jo')
            ->innerJoin(JournalAuthorsLcp::tableName(), "jo.id = " . JournalAuthorsLcp::tableName() . ".author_id")
            ->where([JournalAuthorsLcp::tableName() . '.journal_id' => $id])->all();


        return $this->render('view', [
            'model' => $this->findModel($id),
            'journal_authors' => ArrayHelper::map($journalAuthors, 'id', function ($author) {
                return $author->first_name . ' ' . $author->last_name;
            }),
        ]);
    }

    public function actionCreate(): string|Response
    {
        $form = new JournalForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Journal created.');

            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $form,
            'authors' => ArrayHelper::map(JournalAuthors::find()->all(), 'id', function ($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $form = new JournalForm($this->findModel($id));

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            Yii::$app->session->setFlash('success', 'Journal updated.');

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $form,
            'authors' => ArrayHelper::map(JournalAuthors::find()->all(), 'id', function ($user) {
                return $user->first_name . ' ' . $user->last_name;
            })
        ]);
    }

    /**
     * Deletes an existing Journal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Journal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Journal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Journal::findOne(['id' => $id])) !== null) {
            return $model;
        }

        $authors = JournalAuthors::find()
//            ->with('getJournalAuthorsLcps')
            ->where(['journal_id' => $id])
            ->all();

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Journal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return JournalArticles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelArticle($id)
    {
        if (($model = JournalArticles::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionArticles($id)
    {

        $searchModel = new JournalArticlesSearch();
        $dataProvider = $searchModel->search($id, $this->request->queryParams);

        return $this->render('article_index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'journalId' => $id
        ]);

    }

    public function actionCreateArticle($journalId): string|Response
    {

        $form = new JournalArticleForm();

        if ($form->load(Yii::$app->request->post()) && $form->save($journalId)) {
            Yii::$app->session->setFlash('success', 'Journal created.');

            return $this->redirect(['articles', 'id' => $journalId]);
        }


        return $this->render('create_article', [
            'model' => $form,
            'journalId' => $journalId
        ]);
    }

    public function actionUpdateArticle(int $id, int $journalId): string|Response
    {
        $form = new JournalArticleForm($this->findModelArticle($id));


        if ($form->load(Yii::$app->request->post()) && $form->save($journalId)) {
            Yii::$app->session->setFlash('success', 'Article updated.');

            return $this->redirect(['articles', 'id' => $journalId]);
        }

        return $this->render('update_article', [
            'model' => $form,
            'journalId' => $journalId
        ]);
    }

    /**
     * Displays a single Journal model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionArticleView($id, $journalId)
    {
        return $this->render('article_view', [
            'model' => $this->findModelArticle($id),
            'journalId' => $journalId
        ]);
    }


    /**
     * Deletes an existing Journal model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionArticleDelete($id, $journalId)
    {

        $this->findModelArticle($id)->delete();
        return $this->redirect(['articles', 'id' => $journalId]);
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

        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/' . Journal::UPLOAD_DIR . '/editorjs';
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
                'url' => '/uploads/' . Journal::UPLOAD_DIR . '/editorjs/' . $fileName,
            ],
        ];
    }
}
