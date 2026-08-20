<?php

namespace backend\modules\journal\controllers;

use backend\modules\journal\models\JournalSectionsForm;
use backend\modules\journal\models\JournalSectionsSearch;
use common\models\Journal;
use common\models\JournalSections;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * JournalSectionsController implements the CRUD actions for JournalSections model.
 */
class JournalSectionsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
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
                ],
            ],
        ];
    }

    /**
     * Lists all JournalSections models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JournalSectionsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);


        $journals = Journal::find()->all();
        $journalsMap = [];
        foreach ($journals as $journal) {
            $journalsMap[$journal->id] = $journal->getDisplayTitle();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'journals' => $journalsMap
        ]);
    }

    /**
     * Displays a single JournalSections model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $journals = Journal::find()->all();
        $journalsMap = [];
        foreach ($journals as $journal) {
            $journalsMap[$journal->id] = $journal->getDisplayTitle();
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'journals' => $journalsMap
        ]);
    }

    /**
     * Creates a new JournalSections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new JournalSectionsForm();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }

        $journals = Journal::find()->all();
        $journalsMap = [];
        foreach ($journals as $journal) {
            $journalsMap[$journal->id] = $journal->getDisplayTitle();
        }


        return $this->render('create', [
            'model' => $model,
            'journals' => $journalsMap
        ]);
    }

    /**
     * Updates an existing JournalSections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new JournalSectionsForm($this->findModel($id));

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $journals = Journal::find()->all();
        $journalsMap = [];
        foreach ($journals as $journal) {
            $journalsMap[$journal->id] = $journal->getDisplayTitle();
        }

        return $this->render('update', [
            'model' => $model,
            'journals' => $journalsMap
        ]);
    }

    /**
     * Deletes an existing JournalSections model.
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
     * Finds the JournalSections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return JournalSections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {

        $model = JournalSections::find()->with('translations')->where(['id' => $id])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested chair does not exist.');
        }
        return $model;
    }
}
