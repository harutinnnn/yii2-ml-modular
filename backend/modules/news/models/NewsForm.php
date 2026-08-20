<?php

namespace backend\modules\news\models;

use common\models\Language;
use common\models\News;
use common\models\NewsMl;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class NewsForm extends Model
{
    public ?News $news = null;
    public int $status = News::STATUS_PUBLISHED;
    public int $category_id = 0;
    public ?string $image = null;
    public array $translations = [];
    public $imageFile = null;

    private ?array $_languages = null;

    public function __construct(?News $news = null, $config = [])
    {
        $this->news = $news;
        parent::__construct($config);

        if ($this->news !== null) {
            $this->status = (int) $this->news->status;
            $this->category_id = (int) $this->news->category_id;
            $this->image = $this->news->image;
            foreach ($this->news->translations as $translation) {
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
            [['status','category_id'], 'required'],
            [['status','category_id'], 'integer'],
            [['status'], 'in', 'range' => array_keys(News::statusOptions())],
            [['translations'], 'safe'],
            [['translations'], 'validateTranslations'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => ['png', 'jpg', 'jpeg', 'gif', 'webp']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'status' => 'Status',
            'category_id' => 'Category',
            'imageFile' => 'Image',
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
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');

        if (!$this->validate()) {
            return false;
        }

        $news = $this->news ?? new News();
        $news->status = $this->status;
        $news->category_id = $this->category_id;

        if ($this->imageFile !== null) {
            $news->image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$news->save()) {
                $this->addErrors($news->getErrors());
                $transaction->rollBack();

                return false;
            }

            NewsMl::deleteAll(['news_id' => $news->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new NewsMl();
                $translation->news_id = $news->id;
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
            $this->news = $news;

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
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/news';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/news/' . $name;
    }
}
