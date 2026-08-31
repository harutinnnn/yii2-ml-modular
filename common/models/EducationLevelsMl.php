<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $education_level_id
 * @property string $lang
 * @property string $title
 * @property string|null $img
 *
 * @property EducationLevels $education_levels
 */
class EducationLevelsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%education_levels_ml}}';
    }

    public function rules(): array
    {
        return [
            [['education_level_id', 'lang', 'title'], 'required'],
            [['education_level_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['education_level_id', 'lang'], 'unique', 'targetAttribute' => ['education_level_id', 'lang']],
            [['education_level_id'], 'exist', 'targetClass' => EducationLevels::class, 'targetAttribute' => ['education_level_id' => 'id']],
            [['img'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'education_level_id' => 'Education Levels',
            'lang' => 'Language',
            'title' => 'Title',
            'img' => 'Image',
        ];
    }

    public function getEducationLevels()
    {
        return $this->hasOne(EducationLevels::class, ['id' => 'education_level_id']);
    }
}
