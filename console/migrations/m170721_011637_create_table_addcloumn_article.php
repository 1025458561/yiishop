<?php

use yii\db\Migration;

class m170721_011637_create_table_addcloumn_article extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m170721_011637_create_table_addcloumn_article cannot be reverted.\n";

        return false;
    }


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->addColumn('article','intro','string(100)');

    }

    public function down()
    {
        echo "m170721_011637_create_table_addcloumn_article cannot be reverted.\n";

        return false;
    }

}
