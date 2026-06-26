<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $faculty_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property Faculties $faculties
 */
class FacultiesMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%faculties_ml}}';
    }

    public function rules(): array
    {
        return [
            [['faculty_id', 'lang', 'title'], 'required'],
            [['faculty_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['faculty_id', 'lang'], 'unique', 'targetAttribute' => ['faculty_id', 'lang']],
            [['faculty_id'], 'exist', 'targetClass' => Faculties::class, 'targetAttribute' => ['faculty_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'faculty_id' => 'Faculty',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getFaculty()
    {
        return $this->hasOne(Faculties::class, ['id' => 'faculty_id']);
    }
}
