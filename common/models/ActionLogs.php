<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "action_logs".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $action
 * @property string|null $entity_type
 * @property int|null $entity_id
 * @property string|null $description
 * @property string|null $old_values
 * @property string|null $new_values
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $request_method
 * @property string|null $request_url
 * @property string $created_at
 *
 * @property User $user
 */
class ActionLogs extends \yii\db\ActiveRecord
{


    public const ACTION_CREATE = 'create';
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';
    public const ACTION_LOGIN = 'login';
    public const ACTION_LOGOUT = 'logout';
    public const ACTION_STATUS_CHANGE = 'status_change';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'action_logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['action', 'entity_type', 'entity_id', 'description', 'old_values', 'new_values', 'ip_address', 'user_agent', 'request_method', 'request_url'], 'default', 'value' => null],
            [['user_id'], 'default', 'value' => 0],
            [['user_id', 'entity_id'], 'integer'],
            [['old_values', 'new_values', 'created_at'], 'safe'],
            [['action', 'entity_type', 'description'], 'string', 'max' => 255],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 500],
            [['request_method'], 'string', 'max' => 10],
            [['request_url'], 'string', 'max' => 1000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'action' => 'Action',
            'entity_type' => 'Entity Type',
            'entity_id' => 'Entity ID',
            'description' => 'Description',
            'old_values' => 'Old Values',
            'new_values' => 'New Values',
            'ip_address' => 'Ip Address',
            'user_agent' => 'User Agent',
            'request_method' => 'Request Method',
            'request_url' => 'Request Url',
            'created_at' => 'Created At',
        ];
    }


    public static function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        array $oldValues = [],
        array $newValues = [],
        ?string $description = null
    ): bool {
        $model = new self();

        $model->user_id = Yii::$app->user->isGuest
            ? null
            : Yii::$app->user->id;

        $model->action = $action;
        $model->entity_type = $entityType;
        $model->entity_id = $entityId;
        $model->description = $description;

        $model->old_values = empty($oldValues)
            ? null
            : json_encode($oldValues, JSON_UNESCAPED_UNICODE);

        $model->new_values = empty($newValues)
            ? null
            : json_encode($newValues, JSON_UNESCAPED_UNICODE);

        if (Yii::$app instanceof \yii\web\Application) {
            $request = Yii::$app->request;

            $model->ip_address = $request->userIP;
            $model->user_agent = $request->userAgent;
            $model->request_method = $request->method;
            $model->request_url = $request->absoluteUrl;
        }

        return $model->save(false);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
