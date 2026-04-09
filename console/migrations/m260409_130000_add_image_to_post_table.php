<?php

use yii\db\Migration;

class m260409_130000_add_image_to_post_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%post}}', 'image', $this->string()->null()->after('status'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%post}}', 'image');
    }
}
