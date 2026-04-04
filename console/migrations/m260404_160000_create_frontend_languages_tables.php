<?php

use yii\db\Migration;

class m260404_160000_create_frontend_languages_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%frontend_languages}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'type' => $this->string(16)->notNull()->defaultValue('content'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%frontend_languages_ml}}', [
            'id' => $this->primaryKey(),
            'frontend_language_id' => $this->integer()->notNull(),
            'lang' => $this->string(8)->notNull(),
            'key' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-frontend_languages_ml-frontend_language_id', '{{%frontend_languages_ml}}', 'frontend_language_id');
        $this->createIndex('ux-frontend_languages_ml-lang', '{{%frontend_languages_ml}}', ['frontend_language_id', 'lang'], true);
        $this->addForeignKey(
            'fk-frontend_languages_ml-frontend_language_id',
            '{{%frontend_languages_ml}}',
            'frontend_language_id',
            '{{%frontend_languages}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-frontend_languages_ml-frontend_language_id', '{{%frontend_languages_ml}}');
        $this->dropTable('{{%frontend_languages_ml}}');
        $this->dropTable('{{%frontend_languages}}');
    }
}
