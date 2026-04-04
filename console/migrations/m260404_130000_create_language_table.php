<?php

use yii\db\Migration;

class m260404_130000_create_language_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%language}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(8)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            'is_default' => $this->boolean()->notNull()->defaultValue(0),
            'is_active' => $this->boolean()->notNull()->defaultValue(1),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $time = time();

        $this->batchInsert('{{%language}}', [
            'code',
            'name',
            'is_default',
            'is_active',
            'sort_order',
            'created_at',
            'updated_at',
        ], [
            ['en', 'English', 1, 1, 10, $time, $time],
            ['am', 'Armenian', 0, 1, 20, $time, $time],
            ['ru', 'Russian', 0, 1, 30, $time, $time],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%language}}');
    }
}
