<?php

use yii\db\Migration;

class m260404_180000_create_sections_and_menu_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%sections}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'key' => $this->string()->notNull()->unique(),
        ]);

        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0),
            'show_in_menu' => $this->boolean()->notNull()->defaultValue(1),
            'position' => $this->integer()->defaultValue(0),
            'content_id' => $this->integer()->null(),
            'image' => $this->string()->null(),
            'header_image' => $this->string()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createTable('{{%menu_ml}}', [
            'id' => $this->primaryKey(),
            'menu_id' => $this->integer()->notNull(),
            'lang' => $this->string(8)->notNull(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'meta_title' => $this->string()->null(),
            'meta_desc' => $this->text()->null(),
            'description' => $this->text()->null(),
            'meta_keywords' => $this->text()->null(),
            'section_id' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-menu-content_id', '{{%menu}}', 'content_id');
        $this->createIndex('idx-menu_ml-menu_id', '{{%menu_ml}}', 'menu_id');
        $this->createIndex('idx-menu_ml-section_id', '{{%menu_ml}}', 'section_id');
        $this->createIndex('ux-menu_ml-menu_lang', '{{%menu_ml}}', ['menu_id', 'lang'], true);

        $this->addForeignKey('fk-menu-content_id', '{{%menu}}', 'content_id', '{{%content}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-menu_ml-menu_id', '{{%menu_ml}}', 'menu_id', '{{%menu}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-menu_ml-section_id', '{{%menu_ml}}', 'section_id', '{{%sections}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-menu_ml-section_id', '{{%menu_ml}}');
        $this->dropForeignKey('fk-menu_ml-menu_id', '{{%menu_ml}}');
        $this->dropForeignKey('fk-menu-content_id', '{{%menu}}');
        $this->dropTable('{{%menu_ml}}');
        $this->dropTable('{{%menu}}');
        $this->dropTable('{{%sections}}');
    }
}
