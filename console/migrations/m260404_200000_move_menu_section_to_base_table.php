<?php

use yii\db\Migration;
use yii\db\Query;

class m260404_200000_move_menu_section_to_base_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%menu}}', 'section_id', $this->integer()->null()->after('content_id'));
        $this->createIndex('idx-menu-section_id', '{{%menu}}', 'section_id');
        $this->addForeignKey('fk-menu-section_id', '{{%menu}}', 'section_id', '{{%sections}}', 'id', 'SET NULL', 'CASCADE');

        $rows = (new Query())
            ->select(['menu_id', 'section_id'])
            ->from('{{%menu_ml}}')
            ->where(['not', ['section_id' => null]])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $assigned = [];
        foreach ($rows as $row) {
            $menuId = (int) $row['menu_id'];
            if (isset($assigned[$menuId])) {
                continue;
            }

            $this->update('{{%menu}}', ['section_id' => $row['section_id']], ['id' => $menuId]);
            $assigned[$menuId] = true;
        }

        $this->dropForeignKey('fk-menu_ml-section_id', '{{%menu_ml}}');
        $this->dropIndex('idx-menu_ml-section_id', '{{%menu_ml}}');
        $this->dropColumn('{{%menu_ml}}', 'section_id');
    }

    public function safeDown()
    {
        $this->addColumn('{{%menu_ml}}', 'section_id', $this->integer()->null()->after('meta_keywords'));
        $this->createIndex('idx-menu_ml-section_id', '{{%menu_ml}}', 'section_id');
        $this->addForeignKey('fk-menu_ml-section_id', '{{%menu_ml}}', 'section_id', '{{%sections}}', 'id', 'SET NULL', 'CASCADE');

        $rows = (new Query())
            ->select(['id', 'section_id'])
            ->from('{{%menu}}')
            ->where(['not', ['section_id' => null]])
            ->all();

        foreach ($rows as $row) {
            $this->update('{{%menu_ml}}', ['section_id' => $row['section_id']], ['menu_id' => (int) $row['id']]);
        }

        $this->dropForeignKey('fk-menu-section_id', '{{%menu}}');
        $this->dropIndex('idx-menu-section_id', '{{%menu}}');
        $this->dropColumn('{{%menu}}', 'section_id');
    }
}
