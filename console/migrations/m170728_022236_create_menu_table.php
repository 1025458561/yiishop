<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_022236_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'menu_name'=>$this->string(20)->comment('菜单名字'),
            'menu_url'=>$this->string(100)->comment('菜单地址'),
            'parent_id'=>$this->integer()->comment('父id'),
            'create_time'=>$this->integer()->comment('添加时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
