<?php

namespace backend\modules\news\models;

use common\models\Language;
use common\models\NewsCategories;
use common\models\NewsCategoriesMl;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class NewsCategoriesForm extends Model
{
    public ?NewsCategories $news_categories = null;
    public int $status = NewsCategories::STATUS_PUBLISHED;
    public array $translations = [];

    private ?array $_languages = null;

    public function __construct(?NewsCategories $news_categories = null, $config = [])
    {
        $this->news_categories = $news_categories;
        parent::__construct($config);

        if ($this->news_categories !== null) {
            $this->status = (int) $this->news_categories->status;
            foreach ($this->news_categories->translations as $translation) {
                $this->translations[$translation->lang] = [
                    'title' => $translation->title,
                ];
            }
        }

        foreach ($this->getLanguages() as $language) {
            $this->translations[$language->code] = array_merge(
                ['title' => ''],
                $this->translations[$language->code] ?? []
            );
        }
    }

    public function rules(): array
    {
        return [
            [['status'], 'required'],
            [['status'], 'integer'],
            [['status'], 'in', 'range' => array_keys(NewsCategories::statusOptions())],
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


        $news_categories = $this->news_categories ?? new NewsCategories();
        $news_categories->status = $this->status;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$news_categories->save()) {
                $this->addErrors($news_categories->getErrors());
                $transaction->rollBack();

                return false;
            }


            NewsCategoriesMl::deleteAll(['news_categories_id' => $news_categories->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new NewsCategoriesMl();
                $translation->news_categories_id = $news_categories->id;
                $translation->lang = $language->code;
                $translation->title = trim((string) $this->translations[$language->code]['title']);

                if (!$translation->save()) {
                    $this->addErrors($translation->getErrors());
                    $transaction->rollBack();

                    return false;
                }
            }

            $transaction->commit();
            $this->news_categories = $news_categories;

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
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/news_categories';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/news_categories/' . $name;
    }
}
