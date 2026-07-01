<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_articles".
 *
 * @property int $id
 * @property int $journal_id
 * @property string|null $doi
 * @property int|null $first_page
 * @property int|null $last_page
 * @property string|null $received_at
 * @property string|null $accepted_at
 * @property string|null $published_at
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $abstract
 * @property string|null $keywords
 * @property string|null $pdf_file
 * @property string|null $doi_suffix
 *
 * @property ArticleAuthors[] $articleAuthors
 * @property User[] $authors
 * @property Journal $journal
 * @property JournalArticlesMl[] $translations
 */
class JournalArticles extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_SUBMITTED = 'submitted';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_REVISION_REQUIRED = 'revision_required';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doi', 'first_page', 'last_page', 'received_at', 'accepted_at', 'published_at', 'abstract', 'keywords', 'pdf_file'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'submitted'],
            [['journal_id'], 'required'],
            [['journal_id', 'first_page', 'last_page'], 'integer'],
            [['received_at', 'accepted_at', 'published_at', 'created_at', 'updated_at'], 'safe'],
            [['status', 'abstract', 'keywords'], 'string'],
            [['doi', 'pdf_file','doi_suffix'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
//            [['journal_id'], 'unique', 'targetAttribute' => ['journal_id']],
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
            'doi' => 'Doi',
            'first_page' => 'First Page',
            'last_page' => 'Last Page',
            'received_at' => 'Received At',
            'accepted_at' => 'Accepted At',
            'published_at' => 'Published At',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'abstract' => 'Abstract',
            'keywords' => 'Keywords',
            'pdf_file' => 'Pdf File',
            'doi_suffix' => 'Doi suffix',
        ];
    }

    /**
     * Gets query for [[ArticleAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticleAuthors()
    {
        return $this->hasMany(ArticleAuthors::class, ['article_id' => 'id']);
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(User::class, ['id' => 'author_id'])->viaTable('article_authors', ['article_id' => 'id']);
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

    /**
     * Gets query for [[JournalArticlesMls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(JournalArticlesMl::class, ['article_id' => 'id']);
    }

    public function getTranslation(string $lang): ?JournalArticlesMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public function getDisplayTitle(): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();
        if ($defaultLanguage) {
            $translation = $this->getTranslation($defaultLanguage);
            if ($translation !== null && $translation->title !== '') {
                return $translation->title;
            }
        }

        foreach ($this->translations as $translation) {
            if ($translation->title !== '') {
                return $translation->title;
            }
        }

        return 'Untitled';
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_SUBMITTED => 'submitted',
            self::STATUS_UNDER_REVIEW => 'under_review',
            self::STATUS_REVISION_REQUIRED => 'revision_required',
            self::STATUS_ACCEPTED => 'accepted',
            self::STATUS_REJECTED => 'rejected',
            self::STATUS_PUBLISHED => 'published',
            self::STATUS_ARCHIVED => 'archived',
        ];
    }

    public static function statusLabels()
    {
        return [
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_UNDER_REVIEW => 'Under review',
            self::STATUS_REVISION_REQUIRED => 'Revision required',
            self::STATUS_ACCEPTED => 'Accepted',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_ARCHIVED => 'Archived',
        ];
    }

    public function getStatusLabel(): string
    {
        return self::statusLabels()[$this->status] ?? 'Unknown';
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    /**
     * @return bool
     */
    public function isStatusSubmitted()
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function setStatusToSubmitted()
    {
        $this->status = self::STATUS_SUBMITTED;
    }

    /**
     * @return bool
     */
    public function isStatusUnderreview()
    {
        return $this->status === self::STATUS_UNDER_REVIEW;
    }

    public function setStatusToUnderreview()
    {
        $this->status = self::STATUS_UNDER_REVIEW;
    }

    /**
     * @return bool
     */
    public function isStatusRevisionrequired()
    {
        return $this->status === self::STATUS_REVISION_REQUIRED;
    }

    public function setStatusToRevisionrequired()
    {
        $this->status = self::STATUS_REVISION_REQUIRED;
    }

    /**
     * @return bool
     */
    public function isStatusAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function setStatusToAccepted()
    {
        $this->status = self::STATUS_ACCEPTED;
    }

    /**
     * @return bool
     */
    public function isStatusRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function setStatusToRejected()
    {
        $this->status = self::STATUS_REJECTED;
    }

    /**
     * @return bool
     */
    public function isStatusPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    public function setStatusToPublished()
    {
        $this->status = self::STATUS_PUBLISHED;
    }

    /**
     * @return bool
     */
    public function isStatusArchived()
    {
        return $this->status === self::STATUS_ARCHIVED;
    }

    public function setStatusToArchived()
    {
        $this->status = self::STATUS_ARCHIVED;
    }
}
