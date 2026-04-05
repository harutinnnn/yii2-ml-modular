<?php

namespace common\models;

use yii\db\ActiveRecord;

class Setting extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%settings}}';
    }

    public function rules(): array
    {
        return [
            [['key', 'title'], 'required'],
            [['value'], 'string'],
            [['key', 'title'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
            'title' => 'Title',
        ];
    }

    public function beforeSave($insert): bool
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->key = trim((string) $this->key);
        $this->title = trim((string) $this->title);

        return true;
    }
}
