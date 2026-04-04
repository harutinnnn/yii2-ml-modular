<?php

use yii\db\Migration;

class m260404_210000_add_menu_parent_id extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%menu}}', 'parent_id', $this->integer()->null()->after('section_id'));
        $this->createIndex('idx-menu-parent_id', '{{%menu}}', 'parent_id');
        $this->addForeignKey('fk-menu-parent_id', '{{%menu}}', 'parent_id', '{{%menu}}', 'id', 'SET NULL', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-menu-parent_id', '{{%menu}}');
        $this->dropIndex('idx-menu-parent_id', '{{%menu}}');
        $this->dropColumn('{{%menu}}', 'parent_id');
    }
}
