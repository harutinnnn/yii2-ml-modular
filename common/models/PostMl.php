<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $post_id
 * @property string $lang
 * @property string $title
 * @property string $text
 *
 * @property Post $post
 */
class PostMl extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%post_ml}}';
    }

    public function rules(): array
    {
        return [
            [['post_id', 'lang', 'title', 'text'], 'required'],
            [['post_id'], 'integer'],
            [['text'], 'string'],
            [['lang'], 'string', 'max' => 8],
            [['title'], 'string', 'max' => 255],
            [['post_id', 'lang'], 'unique', 'targetAttribute' => ['post_id', 'lang']],
            [['post_id'], 'exist', 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post',
            'lang' => 'Language',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Post::class, ['id' => 'post_id']);
    }
}
