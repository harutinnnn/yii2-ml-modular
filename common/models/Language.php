<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property int $is_default
 * @property int $is_active
 * @property int|null $sort_order
 * @property int $created_at
 * @property int $updated_at
 */
class Language extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%language}}';
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules(): array
    {
        return [
            [['code', 'name'], 'required'],
            [['is_default', 'is_active'], 'default', 'value' => 1],
            [['is_default', 'is_active', 'sort_order'], 'integer'],
            [['code'], 'string', 'max' => 8],
            [['name'], 'string', 'max' => 64],
            [['code'], 'match', 'pattern' => '/^[a-z]{2,8}(?:-[A-Z]{2})?$/'],
            [['code'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'is_default' => 'Default',
            'is_active' => 'Active',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->code = strtolower(trim((string) $this->code));
        $this->name = trim((string) $this->name);

        return true;
    }

    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ((int) $this->is_default === 1) {
            static::updateAll(['is_default' => 0], ['and', ['!=', 'id', $this->id], ['is_default' => 1]]);
        }
    }
}
