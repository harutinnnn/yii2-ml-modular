<?php

namespace backend\controllers;

use common\components\UserHelper;
use common\components\UserRoles;
use common\models\ConversationMembers;
use common\models\Conversations;
use backend\models\ConversationsSearch;
use common\models\EducationLevels;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConversationsController implements the CRUD actions for Conversations model.
 */
class ConversationsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all Conversations models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConversationsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $moderators = UserHelper::getUsersByRoles([UserRoles::ADMIN, UserRoles::TEACHER]);



        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'moderators' => ArrayHelper::map($moderators,
                'id',
                function ($moderator) {
                    return UserHelper::getUserName($moderator);
                }),
        ]);
    }

    /**
     * Displays a single Conversations model.
     * @param string $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Conversations model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Conversations();
        $model->created_by = Yii::$app->user->id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        $moderators = UserHelper::getUsersByRoles([UserRoles::ADMIN, UserRoles::TEACHER]);

        return $this->render('create', [
            'model' => $model,
            'moderators' => ArrayHelper::map($moderators,
                'id',
                function ($moderator) {
                    return UserHelper::getUserName($moderator);
                }),
        ]);
    }

    /**
     * Updates an existing Conversations model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        $moderators = UserHelper::getUsersByRoles([UserRoles::ADMIN, UserRoles::TEACHER]);

        return $this->render('update', [
            'model' => $model,
            'moderators' => ArrayHelper::map($moderators,
                'id',
                function ($moderator) {
                    return UserHelper::getUserName($moderator);
                }),
        ]);
    }

    /**
     * Deletes an existing Conversations model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Conversations model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id ID
     * @return Conversations the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Conversations::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionMembers($conversation_id)
    {

        if (!intval($conversation_id)) {
            return $this->redirect('/admin/conversations');
        }

        $conversation = Conversations::findOne(intval($conversation_id));
        if (!$conversation) {
            return $this->redirect('/admin/conversations');
        }


        $conversationMembers = ConversationMembers::find()
            ->innerJoin('user u', 'u.id = conversation_members.user_id')
            ->innerJoin('auth_assignment aa', 'aa.user_id = u.id')
            ->where(['not in', 'aa.item_name', [UserRoles::APPLICANT]])
            ->andWhere(['conversation_id' => $conversation_id])
            ->all();

        $conversationMemberIds = array_column($conversationMembers, 'user_id');


        $users = User::find()
            ->innerJoin('auth_assignment aa', 'aa.user_id = user.id')
            ->where(['not in', 'aa.item_name', [UserRoles::APPLICANT]])
            ->all();
        $users = array_map(function ($user) {
            $first_name = ($user->additional->first_name ?? '');
            $last_name = ($user->additional->last_name ?? '');
            $full_name = ($user->full_name ?? '');
            $email = $user->email;

            if (strlen(trim($first_name)) || strlen(trim($last_name))) {
                $name = $first_name . ' ' . $last_name;
            } else if (strlen(trim($full_name))) {
                $name = $full_name;
            } else {
                $name = $email;
            }
            $roles = Yii::$app->authManager->getRolesByUser($user->id);
            $name .= ' - (' . implode(',', array_keys($roles)) . ')';

            return [
                'id' => $user->id,
                'name' => $name
            ];

        }, $users);


        return $this->render('members', [
            'users' => $users,
            'conversationMemberIds' => $conversationMemberIds,
            "conversation_id" => $conversation_id
        ]);
    }


    public function actionUpdateAssignment()
    {


        $userId = Yii::$app->request->post('userId');
        $action = Yii::$app->request->post('action');
        $conversation_id = Yii::$app->request->post('conversation_id');


        $conversation = Conversations::findOne(intval($conversation_id));
        if ($conversation) {
            $user = User::findOne(intval($userId));
            if ($user) {
                if (in_array($action, [Conversations::ACTION_ASSIGN, Conversations::ACTION_REMOVE])) {

                    if ($action == Conversations::ACTION_ASSIGN) {


                        $conversationMember = new ConversationMembers();
                        $conversationMember->conversation_id = $conversation->id;
                        $conversationMember->user_id = $user->id;
                        $conversationMember->save();

                    } else if ($action == Conversations::ACTION_REMOVE) {

                        $conversationMember = ConversationMembers::find()->where(['conversation_id' => $conversation->id, 'user_id' => $user->id])->one();
                        $conversationMember->delete();
                    }

                }
            }

        }

    }

}
