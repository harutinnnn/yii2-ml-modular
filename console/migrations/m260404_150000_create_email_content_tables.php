<?php

use yii\db\Migration;

class m260404_150000_create_email_content_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%emailContent}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%emailContent_ml}}', [
            'id' => $this->primaryKey(),
            'email_content_id' => $this->integer()->notNull(),
            'lang' => $this->string(8)->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-emailContent_ml-email_content_id', '{{%emailContent_ml}}', 'email_content_id');
        $this->createIndex('ux-emailContent_ml-content_lang', '{{%emailContent_ml}}', ['email_content_id', 'lang'], true);
        $this->addForeignKey(
            'fk-emailContent_ml-email_content_id',
            '{{%emailContent_ml}}',
            'email_content_id',
            '{{%emailContent}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-emailContent_ml-email_content_id', '{{%emailContent_ml}}');
        $this->dropTable('{{%emailContent_ml}}');
        $this->dropTable('{{%emailContent}}');
    }
}
