<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_ml".
 *
 * @property int $id
 * @property int $journal_id
 * @property string $lang
 * @property string $title
 * @property string|null $description
 * @property string|null $aims_scope
 * @property string|null $ethical_policy
 * @property string|null $publication_policy
 * @property string|null $review_policy
 * @property string|null $open_access_policy
 * @property string|null $indexing_info
 *
 * @property Journal $journal
 */
class JournalMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'aims_scope', 'ethical_policy', 'publication_policy', 'review_policy', 'open_access_policy', 'indexing_info'], 'default', 'value' => null],
            [['journal_id', 'lang', 'title'], 'required'],
            [['journal_id'], 'integer'],
            [['description', 'aims_scope', 'ethical_policy', 'publication_policy', 'review_policy', 'open_access_policy', 'indexing_info'], 'string'],
            [['lang'], 'string', 'max' => 3],
            [['title'], 'string', 'max' => 255],
            [['journal_id', 'lang'], 'unique', 'targetAttribute' => ['journal_id', 'lang']],
            [['journal_id'], 'exist', 'skipOnError' => true, 'targetClass' => Journal::class, 'targetAttribute' => ['journal_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'journal_id' => 'Journal ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'description' => 'Description',
            'aims_scope' => 'Aims Scope',
            'ethical_policy' => 'Ethical Policy',
            'publication_policy' => 'Publication Policy',
            'review_policy' => 'Review Policy',
            'open_access_policy' => 'Open Access Policy',
            'indexing_info' => 'Indexing Info',
        ];
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
