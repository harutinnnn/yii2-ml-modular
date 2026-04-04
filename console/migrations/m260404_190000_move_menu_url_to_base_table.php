<?php

use yii\db\Migration;
use yii\db\Query;

class m260404_190000_move_menu_url_to_base_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%menu}}', 'url', $this->string()->null()->after('content_id'));

        $rows = (new Query())
            ->select(['menu_id', 'url'])
            ->from('{{%menu_ml}}')
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $assigned = [];
        foreach ($rows as $row) {
            $menuId = (int) $row['menu_id'];
            if (isset($assigned[$menuId])) {
                continue;
            }

            $this->update('{{%menu}}', ['url' => $row['url']], ['id' => $menuId]);
            $assigned[$menuId] = true;
        }

        $this->update('{{%menu}}', ['url' => ''], ['url' => null]);
        $this->alterColumn('{{%menu}}', 'url', $this->string()->notNull());
        $this->createIndex('ux-menu-url', '{{%menu}}', 'url', true);

        $this->dropColumn('{{%menu_ml}}', 'url');
    }

    public function safeDown()
    {
        $this->addColumn('{{%menu_ml}}', 'url', $this->string()->null()->after('title'));

        $rows = (new Query())
            ->select(['id', 'url'])
            ->from('{{%menu}}')
            ->all();

        foreach ($rows as $row) {
            $this->update('{{%menu_ml}}', ['url' => $row['url']], ['menu_id' => (int) $row['id']]);
        }

        $this->alterColumn('{{%menu_ml}}', 'url', $this->string()->notNull());
        $this->dropIndex('ux-menu-url', '{{%menu}}');
        $this->dropColumn('{{%menu}}', 'url');
    }
}
