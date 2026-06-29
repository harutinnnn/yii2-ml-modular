<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_authors_lcp".
 *
 * @property int $journal_id
 * @property int $author_id
 *
 * @property JournalAuthors $author
 * @property Journal $journal
 */
class JournalAuthorsLcp extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_authors_lcp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['journal_id', 'author_id'], 'required'],
            [['journal_id', 'author_id'], 'integer'],
            [['journal_id', 'author_id'], 'unique', 'targetAttribute' => ['journal_id', 'author_id']],
            [['journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::class, 'targetAttribute' => ['journal_id' => 'id']],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => JournalAuthors::class, 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'journal_id' => 'Journal ID',
            'author_id' => 'Author ID',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(JournalAuthors::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[Journal]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournal()
    {
        return $this->hasOne(Journal::class, ['id' => 'journal_id']);
    }

}
