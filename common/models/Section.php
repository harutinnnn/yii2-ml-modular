<?php

namespace common\models;

use yii\db\ActiveRecord;

class Section extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%sections}}';
    }

    public function rules(): array
    {
        return [
            [['title', 'key', 'position'], 'required'],
            [['position'], 'integer'],
            [['title', 'key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'key' => 'Key',
            'position' => 'Position',
        ];
    }
}
