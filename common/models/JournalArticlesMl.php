<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "journal_articles_ml".
 *
 * @property int $id
 * @property int $article_id
 * @property string $lang
 * @property string $title
 * @property string|null $abstract
 * @property string|null $keywords
 * @property string|null $description
 *
 * @property JournalArticles $article
 */
class JournalArticlesMl extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'journal_articles_ml';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['abstract', 'keywords','description'], 'default', 'value' => null],
            [['article_id', 'lang', 'title'], 'required'],
            [['article_id'], 'integer'],
            [['abstract', 'keywords','description'], 'string'],
            [['lang'], 'string', 'max' => 3],
            [['title'], 'string', 'max' => 255],
            [['article_id', 'lang'], 'unique', 'targetAttribute' => ['article_id', 'lang']],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => JournalArticles::class, 'targetAttribute' => ['article_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'lang' => 'Lang',
            'title' => 'Title',
            'abstract' => 'Abstract',
            'keywords' => 'Keywords',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Article]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(JournalArticles::class, ['id' => 'article_id']);
    }

}
