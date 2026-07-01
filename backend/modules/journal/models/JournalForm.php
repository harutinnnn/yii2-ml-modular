<?php

namespace backend\modules\journal\models;

use common\models\Journal;
use common\models\JournalAuthorsLcp;
use common\models\JournalMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class JournalForm extends Model
{
    public ?Journal $journal = null;
    public string $status = Journal::STATUS_ACTIVE;
    public int $year = 0;
    public int $number = 1;
    public string $doi_prefix = "";
    public string  $doi_suffix = "";
    public ?string $cover_image = null;
    public array $translations = [];
    public $imageFile = null;

    public  $authors;

    private ?array $_languages = null;

    public function __construct(?Journal $journal = null, $config = [])
    {
        $this->journal = $journal;
        parent::__construct($config);

        if ($this->journal !== null) {

            $this->status = (string)$this->journal->status;
            $this->year = $this->journal->year;
            $this->number = $this->journal->number;
            $this->doi_prefix = $this->journal->doi_prefix;
            $this->doi_suffix = $this->journal->doi_suffix;
            $this->cover_image = $this->journal->cover_image;

            foreach ($this->journal->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'description' => $translation->description,
                ];
            }

            if($journal->id){
                $this->authors = ArrayHelper::getColumn(JournalAuthorsLcp::find()->where(['journal_id' => $journal->id])->all(),['author_id']);
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
            [['status', 'year', 'number', 'doi_prefix'], 'required'],
            [['status', 'doi_prefix','doi_suffix'], 'string'],
            [['year', 'number'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Journal::statusOptions())],
            [['translations','authors'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'year' => 'Year',
            'number' => 'Number',
            'doi_prefix' => 'Doi prefix',
            'doi_suffix' => 'Doi Suffix',
            'imageFile' => 'Image',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $description = trim((string)($data['description'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            if ($description === '') {
                $this->addError("translations[{$language->code}][description]", "Description is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {

        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');


        if (!$this->validate()) {
            return false;
        }


        $journal = $this->journal ?? new Journal();
        $journal->status = $this->status;
        $journal->year = $this->year;
        $journal->number = $this->number;
        $journal->doi_prefix = $this->doi_prefix;





        if ($this->imageFile !== null) {
            $journal->cover_image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if (!$journal->save()) {
                $this->addErrors($journal->getErrors());
                $transaction->rollBack();

                return false;
            }

            JournalMl::deleteAll(['journal_id' => $journal->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new JournalMl();
                $translation->journal_id = $journal->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->description = trim((string)$this->translations[$language->code]['description']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            if ($journal->id && count($this->authors)) {

                JournalAuthorsLcp::deleteAll(['journal_id' => $journal->id]);
                foreach ($this->authors as $aithor) {
                    $newAuthorLcp = new JournalAuthorsLcp();
                    $newAuthorLcp->journal_id = $journal->id;
                    $newAuthorLcp->author_id = $aithor;
                    $newAuthorLcp->save();
                }

            }


            $transaction->commit();
            $this->journal = $journal;

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
