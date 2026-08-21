<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $announcements_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property Announcements $announcements
 */
class AnnouncementsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%announcements_ml}}';
    }

    public function rules(): array
    {
        return [
            [['announcements_id', 'lang', 'title', 'text'], 'required'],
            [['announcements_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['announcements_id', 'lang'], 'unique', 'targetAttribute' => ['announcements_id', 'lang']],
            [['announcements_id'], 'exist', 'targetClass' => Announcements::class, 'targetAttribute' => ['announcements_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'announcements_id' => 'Announcements',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getAnnouncements()
    {
        return $this->hasOne(Announcements::class, ['id' => 'announcements_id']);
    }
}
