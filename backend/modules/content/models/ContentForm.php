<?php

namespace backend\modules\content\models;

use common\models\Content;
use common\models\ContentMl;
use common\models\Language;
use Yii;
use yii\base\Model;

class ContentForm extends Model
{
    public ?Content $content = null;
    public int $status = Content::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?Content $content = null, $config = [])
    {
        $this->content = $content;
        parent::__construct($config);

        if ($this->content !== null) {
            $this->status = (int) $this->content->status;
            foreach ($this->content->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                    'text' => $translation->text,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => '', 'text' => ''],
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
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
        ];
    }

    public function validateTranslations(string $attribute): void
    {
        foreach ($this->getLanguages() as $language) {
            $data = $this->translations[$language->code] ?? [];
            $title = trim((string) ($data['title'] ?? ''));
            $text = trim((string) ($data['text'] ?? ''));

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
        if (!$this->validate()) {
            return false;
        }

        $content = $this->content ?? new Content();
        $content->status = $this->status;

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
                $translation->title = trim((string) $this->translations[$language->code]['title']);
                $translation->text = trim((string) $this->translations[$language->code]['text']);

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
}
