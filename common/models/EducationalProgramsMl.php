<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $educational_program_id
 * @property string $lang
 * @property string $title
 * @property string $desc
 * @property string|null $img
 * @property string|null $doc_about_program
 * @property string|null $doc_educational_program_guide
 * @property string|null $doc_subject_list
 * @property string|null $doc_subject_list_remote
 *
 * @property EducationalPrograms $education_programs
 */
class EducationalProgramsMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%educational_programs_ml}}';
    }

    public function rules(): array
    {
        return [
            [['educational_program_id', 'lang', 'title','desc'], 'required'],
            [['educational_program_id'], 'integer'],
            [['lang'], 'string', 'max' => 8],
            [['title','doc_about_program','doc_educational_program_guide','doc_subject_list','doc_subject_list_remote'], 'string', 'max' => 255],
            [['desc'], 'string'],
            [['educational_program_id', 'lang'], 'unique', 'targetAttribute' => ['educational_program_id', 'lang']],
            [['educational_program_id'], 'exist', 'targetClass' => EducationalPrograms::class, 'targetAttribute' => ['educational_program_id' => 'id']],
            [['img'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'educational_program_id' => 'Education Program',
            'lang' => 'Language',
            'title' => 'Title',
            'desc' => 'Description',
            'img' => 'Image',
            'doc_about_program' => 'Document about program',
            'doc_educational_program_guide' => 'Document educational program guide',
            'doc_subject_list' => 'Document subject list',
            'doc_subject_list_remote' => 'Document subject list remote',

        ];
    }

    public function getEducationalPrograms()
    {
        return $this->hasOne(EducationalPrograms::class, ['id' => 'educational_program_id']);
    }
}
