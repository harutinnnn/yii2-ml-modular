<?php

namespace frontend\models;

use common\models\ScientificPortfolio;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class ScientificPortfolioForm extends Model
{

    public $id;
    public $user_id;
    public $job_title;
    public $type;
    public $content_type;
    public $file_or_url;
    public $created_at;
    public $updated_at;


    public $file;


    public function rules()
    {
        return [
            [['user_id', 'job_title', 'type', 'content_type'], 'required'],

            [['user_id'], 'integer'],

            [['job_title', 'type', 'content_type', 'file_or_url'], 'string'],

            // Required when content_type = url
            ['file_or_url', 'required',
                'when' => function ($model) {
                    return $model->content_type === ScientificPortfolio::CONTENT_TYPE_URL;
                },
                'whenClient' => "function (attribute, value) {
                    return $('#scientificportfolioform-content_type').val() === 'url';
                }",
            ],

            // Validate URL
            ['file_or_url', 'url',
                'when' => function ($model) {
                    return $model->content_type === ScientificPortfolio::CONTENT_TYPE_URL;
                },
            ],

            // File required when content_type != url
            ['file', 'file',
                'skipOnEmpty' => true,
                'when' => function ($model) {
                    return $model->content_type !== ScientificPortfolio::CONTENT_TYPE_URL;
                },
                'extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                'maxSize' => 10 * 1024 * 1024,
            ],
        ];
    }


    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $scientificPortfolio = new ScientificPortfolio();

        if ($this->content_type === ScientificPortfolio::CONTENT_TYPE_FILE) {

            if (!$this->file instanceof UploadedFile) {
                $this->addError('file', 'Please select a file.');
                return false;
            }

            $uploadDir = Yii::getAlias('@frontend/web/uploads/scientific-portfolio');

            FileHelper::createDirectory($uploadDir);

            // Generate unique filename
            $fileName = Yii::$app->security->generateRandomString(32)
                . '.' . strtolower($this->file->extension);

            $filePath = $uploadDir . '/' . $fileName;

            // Upload file
            if (!$this->file->saveAs($filePath)) {
                $this->addError('file', 'File upload failed.');
                return false;
            }

            // Save relative path to database
            $this->file_or_url = '/uploads/scientific-portfolio/' . $fileName;
        }

        // Copy attributes AFTER processing the file
        $scientificPortfolio->setAttributes([
            'user_id' => $this->user_id,
            'job_title' => $this->job_title,
            'type' => $this->type,
            'content_type' => $this->content_type,
            'file_or_url' => $this->file_or_url,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        if (!$scientificPortfolio->save()) {

            // Delete uploaded file if DB saving failed
            if (isset($filePath) && is_file($filePath)) {
                unlink($filePath);
            }

            $this->addError('file_or_url', 'Unable to save portfolio.');
            return false;
        }

        $this->id = $scientificPortfolio->id;

        return true;
    }

    public function rulesOld()
    {
        return
            [
                [['user_id', 'job_title', 'type', 'content_type', 'file_or_url'], 'required'],
                [['user_id'], 'integer'],
                [['user_id', 'job_title', 'type', 'content_type', 'file_or_url'], 'string'],
                [['file'], 'file',
                    'skipOnEmpty' => true,
                    'extensions' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],
                    'maxSize' => 10 * 1024 * 1024, // 10 MB
                ],
            ];
    }

}