<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $events_id
 * @property string $lang
 * @property string $title
 * @property string $text
 * @property string $place
 * @property string $organiser
 * @property string $contact
 *
 * @property Events $events
 */
class EventsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%events_ml}}';
    }

    public function rules(): array
    {
        return [
            [['events_id', 'lang', 'title','place','organiser','contact', 'text'], 'required'],
            [['events_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title','place','organiser','contact'], 'string', 'max' => 255],
            [['events_id', 'lang'], 'unique', 'targetAttribute' => ['events_id', 'lang']],
            [['events_id'], 'exist', 'targetClass' => Events::class, 'targetAttribute' => ['events_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'events_id' => 'Events',
            'lang' => 'Language',
            'title' => 'Title',
            'organiser' => 'Organiser',
            'place' => 'Place',
            'contact' => 'Contact',
            'text' => 'Text',
        ];
    }

    public function getEvents()
    {
        return $this->hasOne(EventsMl::class, ['id' => 'events_id']);
    }
}
