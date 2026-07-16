<?php

namespace backend\modules\journal\models;

use common\models\JournalSections;
use common\models\JournalSectionsMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class JournalSectionsForm extends Model
{
    public ?JournalSections $journalSections = null;
    public int $status = JournalSections::STATUS_ACTIVE;
    public int $journal_id = 0;
    public string $journal_section_type = JournalSections::JOURNAL_SECTION_TYPE_PORPOSE_AND_VISION;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?JournalSections $journalSections = null, $config = [])
    {
        $this->journalSections = $journalSections;
        parent::__construct($config);

        if ($this->journalSections !== null) {
            $this->status = (int) $this->journalSections->status;
            $this->journal_id = (int) $this->journalSections->journal_id;
            $this->journal_section_type = (int) $this->journalSections->journal_section_type;
            foreach ($this->journalSections->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'content' => $translation->content,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'content' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status','journal_id','journal_section_type'], 'required'],
            [['journal_section_type'], 'string'],
            [['status','journal_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(JournalSections::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            ['journal_section_type', 'in', 'range' => array_keys(JournalSections::optsJournalSectionType())],

        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'journal_id' => 'Journal',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string) ($data['title'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $journalSections = $this->journalSections ?? new JournalSections();
        $journalSections->status = $this->status;
        $journalSections->journal_id = $this->journal_id;
        $journalSections->journal_section_type = $this->journal_section_type;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$journalSections->save()) {
                $this->addErrors($journalSections->getErrors());
                $transaction->rollBack();
                return false;
            }

            JournalSectionsMl::deleteAll(['journal_section_id' => $journalSections->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new JournalSectionsMl();
                $translation->journal_section_id = $journalSections->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);
                $translation->content = trim((string) $this->translations[$language->code]['content']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->journalSections = $journalSections;

            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @return Language[]
     */
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
}
