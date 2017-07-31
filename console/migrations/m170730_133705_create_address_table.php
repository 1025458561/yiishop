<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170730_133705_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
       /*     'id' => 'ID',
            'name' => '收货人',
            'city' => '所在地区',
            'address' => '详细地址',
            'tel' => '电话',
            'status' => '设为默认地址',
            'area'=>'省、市、区',*/
            'id' => $this->primaryKey(),
            'name'=>$this->string('30')->comment('收货人'),
            'city'=>$this->string(200)->comment('所在地区'),
            'address'=>$this->string(255)->comment('详细地址'),
            'tel'=>$this->integer()->comment('手机号码'),
            'status'=>$this->integer(1)->comment('是否问默认地址'),
            'area'=>$this->string()->comment('省 市 区'),
            'user_id'=>$this->integer()->comment('用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
