<?php

namespace common\models;

use common\components\I18n;
use common\components\StatusList;
use Yii;

/**
 * This is the model class for table "articles_and_books_requests".
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string $title
 * @property string $type
 * @property string $authors
 * @property int $publication_year
 * @property string|null $magazine_publisher
 * @property string|null $publication_link
 * @property string|null $doi
 * @property string|null $base
 * @property string|null $lang
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class ArticlesAndBooksRequests extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TYPE_ARTICLE = 'article';
    const TYPE_BOOK = 'book';
    const BASE_LOCAL_BASE = 'local_base';
    const BASE_SCOPUS = 'scopus';
    const BASE_WEB_OF_SCIENCE = 'web_of_science';
    const BASE_OTHER_INTERNATIONAL = 'other_international';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles_and_books_requests';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => StatusList::STATUS_PENDING],
            ['status', 'in', 'range' => [StatusList::STATUS_ACTIVE, StatusList::STATUS_INACTIVE, StatusList::STATUS_DELETED, StatusList::STATUS_PENDING, StatusList::STATUS_REJECTED]],
            [['magazine_publisher', 'publication_link', 'doi', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['type'], 'default', 'value' => 'article'],
            [['base'], 'default', 'value' => 'local_base'],
            [['user_id', 'title', 'authors', 'publication_year'], 'required'],
            [['type', 'base', 'lang'], 'string'],
            [['user_id', 'publication_year', 'created_at', 'updated_at'], 'integer'],
            [['publication_year'], 'integer', 'min' => 1000, 'max' => date('Y')],
            [['title', 'authors', 'magazine_publisher', 'publication_link', 'doi'], 'string', 'max' => 255],
            ['type', 'in', 'range' => array_keys(self::optsType())],
            ['base', 'in', 'range' => array_keys(self::optsBase())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'title' => 'Title',
            'type' => 'Type',
            'authors' => 'Authors',
            'publication_year' => 'Publication Year',
            'magazine_publisher' => 'Magazine Publisher',
            'publication_link' => 'Publication Link',
            'doi' => 'Doi',
            'base' => 'Base',
            'lang' => 'Language',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * column type ENUM value labels
     * @return string[]
     */
    public static function optsType()
    {
        return [
            self::TYPE_ARTICLE => I18n::translate(self::TYPE_ARTICLE),
            self::TYPE_BOOK => I18n::translate(self::TYPE_BOOK),
        ];
    }

    /**
     * column base ENUM value labels
     * @return string[]
     */
    public static function optsBase()
    {
        return [
            self::BASE_LOCAL_BASE => I18n::translate(self::BASE_LOCAL_BASE),
            self::BASE_SCOPUS => I18n::translate(self::BASE_SCOPUS),
            self::BASE_WEB_OF_SCIENCE => I18n::translate(self::BASE_WEB_OF_SCIENCE),
            self::BASE_OTHER_INTERNATIONAL => I18n::translate(self::BASE_OTHER_INTERNATIONAL),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
