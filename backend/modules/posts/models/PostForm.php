<?php

namespace backend\modules\posts\models;

use common\models\Language;
use common\models\Post;
use common\models\PostMl;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class PostForm extends Model
{
    public ?Post $post = null;
    public int $status = Post::STATUS_PUBLISHED;
    public ?string $image = null;
    public array $translations = [];
    public $imageFile = null;

    private ?array $_languages = null;

    public function __construct(?Post $post = null, $config = [])
    {
        $this->post = $post;
        parent::__construct($config);

        if ($this->post !== null) {
            $this->status = (int) $this->post->status;
            $this->image = $this->post->image;
            foreach ($this->post->translations as $translation) {
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
            [['status'], 'in', 'range' => array_keys(Post::statusOptions())],
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

        $post = $this->post ?? new Post();
        $post->status = $this->status;

        if ($this->imageFile !== null) {
            $post->image = $this->saveUpload($this->imageFile);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$post->save()) {
                $this->addErrors($post->getErrors());
                $transaction->rollBack();

                return false;
            }

            PostMl::deleteAll(['post_id' => $post->id]);

            foreach ($this->getLanguages() as $language) {
                $translation = new PostMl();
                $translation->post_id = $post->id;
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
            $this->post = $post;

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
        $basePath = dirname(__DIR__, 4) . '/frontend/web/uploads/posts';
        FileHelper::createDirectory($basePath);
        $name = Yii::$app->security->generateRandomString(16) . '.' . $file->extension;
        $file->saveAs($basePath . '/' . $name);

        return '/uploads/posts/' . $name;
    }
}
