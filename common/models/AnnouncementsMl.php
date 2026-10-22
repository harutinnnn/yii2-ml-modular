<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * @property int $id
 * @property int $announcements_id
 * @property string $lang
 * @property string $title
 * @property string $text
 * @property string $slug
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
            [['text','slug'], 'string'],
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
            'slug' => 'Slug',
        ];
    }

    public function getAnnouncements()
    {
        return $this->hasOne(Announcements::class, ['id' => 'announcements_id']);
    }


    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $this->updateAttributes([
                'slug' => Inflector::slug($this->title).'-'.$this->announcements_id
            ]);
        } else {
            if (!$this->doi_suffix) {
                $this->updateAttributes([
                    'slug' => Inflector::slug($this->title).'-'.$this->announcements_id
                ]);
            }
        }
    }
}
