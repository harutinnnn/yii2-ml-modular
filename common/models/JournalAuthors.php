<?php

namespace common\models;

use common\behaviors\TFileUploadBehavior;
use Yii;

/**
 * This is the model class for table "journal_authors".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $bio
 * @property string $img
 *
 * @property JournalAuthorsLcp[] $journalAuthorsLcps
 * @property Journal[] $journals
 */
class JournalAuthors extends \yii\db\ActiveRecord
{

    const UPLOAD_DIR = 'journal-authors';
    public function behaviors()
    {
        return [
            [
                'class' => TFileUploadBehavior::class,
                'attribute' => 'img',
                'scenarios' => ['default'],
                'path' => '@uploads/' . self::UPLOAD_DIR,
                'url' => '@uploadUrl/' . self::UPLOAD_DIR,
            ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_authors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'bio'], 'required'],
            [['bio'], 'string'],
            [['first_name', 'last_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'bio' => 'Bio',
            'img' => 'Author image',
        ];
    }

    /**
     * Gets query for [[JournalAuthorsLcps]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournalAuthorsLcps()
    {
        return $this->hasMany(JournalAuthorsLcp::class, ['author_id' => 'id']);
    }

    /**
     * Gets query for [[Journals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournals()
    {
        return $this->hasMany(Journal::class, ['id' => 'journal_id'])->viaTable('journal_authors_lcp', ['author_id' => 'id']);
    }

}
