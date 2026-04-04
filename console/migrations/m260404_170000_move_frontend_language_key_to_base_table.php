<?php

use yii\db\Migration;
use yii\db\Query;

class m260404_170000_move_frontend_language_key_to_base_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%frontend_languages}}', 'key', $this->string()->null()->after('id'));

        $rows = (new Query())
            ->select(['frontend_language_id', 'key'])
            ->from('{{%frontend_languages_ml}}')
            ->orderBy(['id' => SORT_ASC])
            ->all();

        $assigned = [];
        foreach ($rows as $row) {
            $id = (int) $row['frontend_language_id'];
            if (isset($assigned[$id])) {
                continue;
            }

            $this->update('{{%frontend_languages}}', ['key' => $row['key']], ['id' => $id]);
            $assigned[$id] = true;
        }

        $this->update('{{%frontend_languages}}', ['key' => ''], ['key' => null]);
        $this->alterColumn('{{%frontend_languages}}', 'key', $this->string()->notNull());
        $this->createIndex('ux-frontend_languages-key', '{{%frontend_languages}}', 'key', true);

        $this->dropColumn('{{%frontend_languages_ml}}', 'key');
    }

    public function safeDown()
    {
        $this->addColumn('{{%frontend_languages_ml}}', 'key', $this->string()->null()->after('lang'));

        $rows = (new Query())
            ->select(['id', 'key'])
            ->from('{{%frontend_languages}}')
            ->all();

        foreach ($rows as $row) {
            $this->update(
                '{{%frontend_languages_ml}}',
                ['key' => $row['key']],
                ['frontend_language_id' => (int) $row['id']]
            );
        }

        $this->alterColumn('{{%frontend_languages_ml}}', 'key', $this->string()->notNull());
        $this->dropIndex('ux-frontend_languages-key', '{{%frontend_languages}}');
        $this->dropColumn('{{%frontend_languages}}', 'key');
    }
}
