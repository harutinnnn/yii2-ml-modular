<?php

namespace backend\modules\journal\models;

use common\models\JournalArticles;
use common\models\JournalArticlesMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class JournalArticleForm extends Model
{
    public ?JournalArticles $journalArticle = null;
    public string $status = JournalArticles::STATUS_UNDER_REVIEW;
    public int $journal_id = 0;
    public string $doi = "";
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?JournalArticles $journalArticle = null, $config = [])
    {
        $this->journalArticle = $journalArticle;
        parent::__construct($config);

        if ($this->journalArticle !== null) {

            $this->status = (string)$this->journalArticle->status;
            $this->doi = $this->journalArticle->doi;

            foreach ($this->journalArticle->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'description' => $translation->description,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'description' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status', 'doi'], 'required'],
            [['status',], 'string'],
            [['status'], 'in', 'range' => array_keys(JournalArticles::optsStatus())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'number' => 'Number',
            'doi' => 'Doi prefix',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $description = trim((string)($data['description'] ?? ''));

            if ($description === '') {
                $this->addError("translations[{$language->code}][description]", "Description is required for {$language->name}.");
            }
        }
    }

    public function save($journalId): bool
    {

        $this->journal_id = intval($journalId);

        if (!$this->validate()) {
            return false;
        }


        $journalArticle = $this->journalArticle ?? new JournalArticles();
        $journalArticle->status = $this->status;
        $journalArticle->doi = $this->doi;
        $journalArticle->journal_id = $this->journal_id;


        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$journalArticle->save()) {
                $this->addErrors($journalArticle->getErrors());
                $transaction->rollBack();

                return false;
            }

            JournalArticlesMl::deleteAll(['article_id' => $journalArticle->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new JournalArticlesMl();
                $translation->article_id = $journalArticle->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->description = trim((string)$this->translations[$language->code]['description']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->journalArticle = $journalArticle;

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

    protected function saveUpload(UploadedFile $file): string
    {
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/journal';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/journal/' . $name;
    }
}
