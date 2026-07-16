<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_sections_ml".
 *
 * @property int $id
 * @property int $journal_section_id
 * @property string $lang
 * @property string $title
 * @property string $content
 *
 * @property JournalSections $journalSection
 */
class JournalSectionsMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_sections_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['journal_section_id', 'lang', 'title', 'content'], 'required'],
            [['journal_section_id'], 'integer'],
            [['content'], 'string'],
            [['lang'], 'string', 'max' => 3],
            [['title'], 'string', 'max' => 255],
            [['journal_section_id', 'lang'], 'unique', 'targetAttribute' => ['journal_section_id', 'lang']],
            [['journal_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => JournalSections::class, 'targetAttribute' => ['journal_section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'journal_section_id' => 'Journal Section ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'content' => 'Content',
        ];
    }

    /**
     * Gets query for [[JournalSection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournalSection()
    {
        return $this->hasOne(JournalSections::class, ['id' => 'journal_section_id']);
    }

}
