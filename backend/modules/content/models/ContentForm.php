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
                ['title' => '', 'text' => '', 'quote' => '', 'pre_title' => ''],
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
            [['translations'], 'validateTranslations', 'skipOnEmpty' => false],
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
        if ($this->getCompleteTranslations() === []) {
            $this->addError($attribute, 'Fill in the title and text in at least one language.');
        }
    }

    private function getCompleteTranslations(): array
    {
        $translations = [];
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = is_string($data['title'] ?? null) ? trim($data['title']) : '';
            $text = is_string($data['text'] ?? null) ? trim($data['text']) : '';
            $visibleContent = html_entity_decode(
                strip_tags($text, ['img', 'hr', 'table', 'iframe', 'video', 'audio', 'embed', 'object', 'svg']),
                ENT_QUOTES | ENT_HTML5,
                'UTF-8'
            );

            if ($title !== '' && preg_match('/[^\s\x{200B}\x{FEFF}]/u', $visibleContent)) {
                $translations[$language->code] = [
                    'title' => $title,
                    'text' => $text,
                    'pre_title' => is_string($data['pre_title'] ?? null) ? trim($data['pre_title']) : '',
                    'quote' => is_string($data['quote'] ?? null) ? trim($data['quote']) : '',
                ];
            }
        }

        return $translations;
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

            $translations = $this->getCompleteTranslations();
            ContentMl::deleteAll(['content_id' => $content->id, 'lang' => array_keys($translations)]);

            foreach ($translations as $lang => $data) {
                $translation = new ContentMl();
                $translation->content_id = $content->id;
                $translation->lang = $lang;
                $translation->title = $data['title'];
                $translation->text = $data['text'];
                $translation->pre_title = $data['pre_title'];
                $translation->quote = $data['quote'];

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
