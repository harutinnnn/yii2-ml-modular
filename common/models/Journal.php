<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "journal".
 *
 * @property int $id
 * @property int $year
 * @property int $number
 * @property string $doi_prefix
 * @property string|null $issn_print
 * @property string|null $issn_online
 * @property string|null $logo
 * @property string|null $cover_image
 * @property string|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property JournalArticles[] $journalArticles
 * @property JournalMl[] $translations
 */
class Journal extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%journal}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['issn_print', 'issn_online', 'logo', 'cover_image'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'active'],
            [['year', 'number', 'doi_prefix'], 'required'],
            [['year', 'number'], 'integer'],
            [['status'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['doi_prefix'], 'string', 'max' => 100],
            [['issn_print', 'issn_online'], 'string', 'max' => 50],
            [['logo', 'cover_image'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::statusOptions())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'year' => 'Year',
            'number' => 'Number',
            'doi_prefix' => 'Doi Prefix',
            'issn_print' => 'Issn Print',
            'issn_online' => 'Issn Online',
            'logo' => 'Logo',
            'cover_image' => 'Cover Image',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[JournalArticles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJournalArticles()
    {
        return $this->hasMany(JournalArticles::class, ['journal_id' => 'id']);
    }

    /**
     * Gets query for [[JournalMls]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(JournalMl::class, ['journal_id' => 'id'])->indexBy('lang');
    }

    public function getTranslation(string $lang): ?JournalMl
    {
        $translations = $this->translations;

        return $translations[$lang] ?? null;
    }

    public function getDisplayTitle(): string
    {
        $defaultLanguage = Language::find()->where(['is_default' => 1])->select('code')->scalar();
        if ($defaultLanguage) {
            $translation = $this->getTranslation($defaultLanguage);
            if ($translation !== null && $translation->title !== '') {
                return $translation->title;
            }
        }

        foreach ($this->translations as $translation) {
            if ($translation->title !== '') {
                return $translation->title;
            }
        }

        return 'Untitled';
    }


    /**
     * column status ENUM value labels
     * @return string[]
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
        ];
    }

    public function getStatusLabel(): string
    {
        return self::statusOptions()[$this->status] ?? 'Unknown';
    }
}
