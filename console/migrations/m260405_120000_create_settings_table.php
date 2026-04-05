<?php

use yii\db\Migration;

class m260405_120000_create_settings_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string()->notNull()->unique(),
            'value' => $this->text()->null(),
            'title' => $this->string()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%settings}}');
    }
}
