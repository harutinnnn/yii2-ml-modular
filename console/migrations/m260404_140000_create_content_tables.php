<?php

use yii\db\Migration;

class m260404_140000_create_content_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%content}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%content_ml}}', [
            'id' => $this->primaryKey(),
            'content_id' => $this->integer()->notNull(),
            'lang' => $this->string(8)->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-content_ml-content_id', '{{%content_ml}}', 'content_id');
        $this->createIndex('ux-content_ml-content_lang', '{{%content_ml}}', ['content_id', 'lang'], true);
        $this->addForeignKey(
            'fk-content_ml-content_id',
            '{{%content_ml}}',
            'content_id',
            '{{%content}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-content_ml-content_id', '{{%content_ml}}');
        $this->dropTable('{{%content_ml}}');
        $this->dropTable('{{%content}}');
    }
}
