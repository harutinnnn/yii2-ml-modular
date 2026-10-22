<?php

namespace backend\modules\content\models;

use common\models\Content;
use common\models\ContentMl;
use common\models\Language;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ContentForm extends Model
{
    public ?Content $content = null;
    public int $status = Content::STATUS_PUBLISHED;
    public ?string $image = null;
    public array $translations = [];
    public $imageFile = null;

    private ?array $_languages = null;

    public function __construct(?Content $content = null, $config = [])
    {
        $this->content = $content;
        parent::__construct($config);

        if ($this->content !== null) {
            $this->status = (int)$this->content->status;
            $this->image = $this->content->image;
            foreach ($this->content->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                    'pre_title' => $translation->pre_title,
                    'quote' => $translation->quote,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => '','quote','pre_title'],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(Content::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'imageFile' => 'Image',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string)($data['title'] ?? ''));
            $text = trim((string)($data['text'] ?? ''));
            $pre_title = trim((string)($data['pre_title'] ?? ''));
            $quote = trim((string)($data['quote'] ?? ''));

            if ($title === '') {
                $this->addError("translations[{$language->code}][title]", "Title is required for {$language->name}.");
            }

            if ($text === '') {
                $this->addError("translations[{$language->code}][text]", "Text is required for {$language->name}.");
            }
        }
    }

    public function save(): bool
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            return false;
        }

        $content = $this->content ?? new Content();
        $content->status = $this->status;

        if ($this->imageFile !== null) {
            $content->image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$content->save()) {
                $this->addErrors($content->getErrors());
                $transaction->rollBack();

                return false;
            }

            ContentMl::deleteAll(['content_id' => $content->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new ContentMl();
                $translation->content_id = $content->id;
                $translation->lang = $language->code;
                $translation->title = trim((string)$this->translations[$language->code]['title']);
                $translation->text = trim((string)$this->translations[$language->code]['text']);
                $translation->pre_title = trim((string)$this->translations[$language->code]['pre_title']);
                $translation->quote = trim((string)$this->translations[$language->code]['quote']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->content = $content;

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
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/' . Content::UPLOAD_PATH;
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/' . Content::UPLOAD_PATH . '/' . $name;
    }
}
