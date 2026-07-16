<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_sections".
 *
 * @property int $id
 * @property int $journal_id
 * @property string $journal_section_type
 * @property int $status
 *
 * @property Journal $journal
 * @property ChairsMl[] $translations
 */
class JournalSections extends \yii\db\ActiveRecord
{

    /**
     * Statuses
     */
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * ENUM field values
     */
    const JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION = 'porpose_and_vision';
    const JOURNAL_SECTION_TYPE_EDITORIAL_BOARD = 'editorial_board';
    const JOURNAL_SECTION_TYPE_THEMES = 'themes';
    const JOURNAL_SECTION_TYPE_COPYRIGHT = 'copyright';
    const JOURNAL_SECTION_TYPE_PUBLICATION_ETHICS = 'publication_ethics';
    const JOURNAL_SECTION_TYPE_REVIEW_PROCEDURE = 'review_procedure';
    const JOURNAL_SECTION_TYPE_OPEN_ACCESS = 'open_access';
    const JOURNAL_SECTION_TYPE_INDEXING = 'indexing';
    const JOURNAL_SECTION_TYPE_ANTI_PLAGIARISM_POLICY = 'anti_plagiarism_policy';
    const JOURNAL_SECTION_TYPE_THE_BENEFITS_OD_AI = 'the_benefits_od_AI';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'default', 'value' => 0],
            [['journal_id', 'journal_section_type'], 'required'],
            [['journal_id', 'status'], 'integer'],
            [['journal_section_type'], 'string'],
            ['journal_section_type', 'in', 'range' => array_keys(self::optsJournalSectionType())],
            [['journal_id', 'journal_section_type'], 'unique', 'targetAttribute' => ['journal_id', 'journal_section_type']],
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
            'journal_id' => 'Journal',
            'journal_section_type' => 'Journal Section Type',
            'status' => 'Status',
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

    public static function statusOptions(): array
    {
        return [
            self::STATUS_INACTIVE => 'Pending',
            self::STATUS_ACTIVE => 'Published',
        ];
    }


    /**
     * column journal_section_type ENUM value labels
     * @return string[]
     */
    public static function optsJournalSectionType()
    {
        return [
            self::JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION => 'porpose_and_vision',
            self::JOURNAL_SECTION_TYPE_EDITORIAL_BOARD => 'editorial_board',
            self::JOURNAL_SECTION_TYPE_THEMES => 'themes',
            self::JOURNAL_SECTION_TYPE_COPYRIGHT => 'copyright',
            self::JOURNAL_SECTION_TYPE_PUBLICATION_ETHICS => 'publication_ethics',
            self::JOURNAL_SECTION_TYPE_REVIEW_PROCEDURE => 'review_procedure',
            self::JOURNAL_SECTION_TYPE_OPEN_ACCESS => 'open_access',
            self::JOURNAL_SECTION_TYPE_INDEXING => 'indexing',
            self::JOURNAL_SECTION_TYPE_ANTI_PLAGIARISM_POLICY => 'anti_plagiarism_policy',
            self::JOURNAL_SECTION_TYPE_THE_BENEFITS_OD_AI => 'the_benefits_od_AI',
        ];
    }

    public static function getTypesLabels(): array
    {
        return
            [
                self::JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION => 'Purpose and vision',
                self::JOURNAL_SECTION_TYPE_EDITORIAL_BOARD => 'Editorial board',
                self::JOURNAL_SECTION_TYPE_THEMES => 'Themes',
                self::JOURNAL_SECTION_TYPE_COPYRIGHT => 'Copyright',
                self::JOURNAL_SECTION_TYPE_PUBLICATION_ETHICS => 'Publication ethics',
                self::JOURNAL_SECTION_TYPE_REVIEW_PROCEDURE => 'Review procedure',
                self::JOURNAL_SECTION_TYPE_OPEN_ACCESS => 'Open access',
                self::JOURNAL_SECTION_TYPE_INDEXING => 'Indexing',
                self::JOURNAL_SECTION_TYPE_ANTI_PLAGIARISM_POLICY => 'Anti plagiarism policy',
                self::JOURNAL_SECTION_TYPE_THE_BENEFITS_OD_AI => 'The benefits od AI'];
    }

    /**
     * @return string
     */
    public function displayJournalSectionType()
    {
        return self::optsJournalSectionType()[$this->journal_section_type];
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypePorposeandvision()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION;
    }

    public function setJournalSectionTypeToPorposeandvision()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeEditorialboard()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_EDITORIAL_BOARD;
    }

    public function setJournalSectionTypeToEditorialboard()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_EDITORIAL_BOARD;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeThemes()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_THEMES;
    }

    public function setJournalSectionTypeToThemes()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_THEMES;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeCopyright()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_COPYRIGHT;
    }

    public function setJournalSectionTypeToCopyright()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_COPYRIGHT;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypePublicationethics()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_PUBLICATION_ETHICS;
    }

    public function setJournalSectionTypeToPublicationethics()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_PUBLICATION_ETHICS;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeReviewprocedure()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_REVIEW_PROCEDURE;
    }

    public function setJournalSectionTypeToReviewprocedure()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_REVIEW_PROCEDURE;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeOpenaccess()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_OPEN_ACCESS;
    }

    public function setJournalSectionTypeToOpenaccess()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_OPEN_ACCESS;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeIndexing()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_INDEXING;
    }

    public function setJournalSectionTypeToIndexing()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_INDEXING;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeAntiplagiarismpolicy()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_ANTI_PLAGIARISM_POLICY;
    }

    public function setJournalSectionTypeToAntiplagiarismpolicy()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_ANTI_PLAGIARISM_POLICY;
    }

    /**
     * @return bool
     */
    public function isJournalSectionTypeThebenefitsodai()
    {
        return $this->journal_section_type === self::JOURNAL_SECTION_TYPE_THE_BENEFITS_OD_AI;
    }

    public function setJournalSectionTypeToThebenefitsodai()
    {
        $this->journal_section_type = self::JOURNAL_SECTION_TYPE_THE_BENEFITS_OD_AI;
    }

    public function getLanguages(): array
    {
        if ($this->_languages === null) {
            $this->_languages = Language::find()
                ->where(['is_active' => 1])
                ->orderBy(['sort_order' => SORT_ASC, 'name' => SORT_ASC])
                ->all();
        }

        return $this->_languages;
    }


    public function getTranslations()
    {
        return $this->hasMany(JournalSectionsMl::class, ['journal_section_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?JournalSectionsMl
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
}
