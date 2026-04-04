<?php

use yii\db\Migration;

class m260404_220000_add_position_to_sections_table extends Migration
{
    public function safeUp(): void
    {
        $this->addColumn('{{%sections}}', 'position', $this->integer()->notNull()->defaultValue(0)->after('key'));

        $rows = (new \yii\db\Query())
            ->from('{{%sections}}')
            ->orderBy(['id' => SORT_ASC])
            ->all($this->db);

        foreach ($rows as $index => $row) {
            $this->update('{{%sections}}', ['position' => $index + 1], ['id' => $row['id']]);
        }
    }

    public function safeDown(): void
    {
        $this->dropColumn('{{%sections}}', 'position');
    }
}
