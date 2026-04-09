<?php

use yii\db\Migration;

class m260409_120000_create_post_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%post_ml}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'lang' => $this->string(8)->notNull(),
            'title' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
        ]);

        $this->createIndex('idx-post_ml-post_id', '{{%post_ml}}', 'post_id');
        $this->createIndex('ux-post_ml-post_lang', '{{%post_ml}}', ['post_id', 'lang'], true);
        $this->addForeignKey(
            'fk-post_ml-post_id',
            '{{%post_ml}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-post_ml-post_id', '{{%post_ml}}');
        $this->dropTable('{{%post_ml}}');
        $this->dropTable('{{%post}}');
    }
}
