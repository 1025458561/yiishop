<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m170724_024931_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(20)->comment('名字'),
            'password'=>$this->string(255)->comment('密码'),
            'create_time'=>$this->integer()->comment('添加时间'),
            'last_login_time'=>$this->integer()->comment('最后登陆时间'),
            'last_login_ip'=>$this->string(50)->comment('最后登录ip地址')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
