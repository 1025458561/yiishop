<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170803_072021_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
  /*      id	primaryKey
member_id	int	用户id
name	varchar(50)	收货人
province	varchar(20)	省
city	varchar(20)	市
area	varchar(20)	县
address	varchar(255)	详细地址
tel	char(11)	电话号码
delivery_id	int	配送方式id
delivery_name	varchar	配送方式名称
delivery_price	float	配送方式价格
payment_id	int	支付方式id
payment_name	varchar	支付方式名称
total	decimal	订单金额
status	int	订单状态（0已取消1待付款2待发货3待收货4完成）
trade_no	varchar	第三方支付交易号
create_time	int	创建时间*/
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment('用户id'),
            'name'=>$this->string('50')->comment('用户名'),
            'province'=>$this->string(20)->comment('省'),
            'city'=>$this->string(20)->comment('市'),
            'area'=>$this->string(20)->comment('县'),
            'address'=>$this->string(255)->comment('详细地址'),
            'tel'=>$this->char(11)->comment('电话'),
            'delivery_id'=>$this->integer()->comment('配送方式id'),
            'delivery_name'=>$this->string()->comment('配送方式名称'),
            'delivery_price'=>$this->float()->comment('配送方式价格'),
            'payment_id'=>$this->float()->comment('配送方式id'),
            'payment_name'=>$this->string()->comment('支付方式名称'),
            'total'=>$this->decimal(10,2)->comment('总金额'),
            'status'=>$this->integer()->comment('订单状态'),
            'trade_no'=>$this->string()->comment('第三方支付方式'),
            'create_time'=>$this->integer()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
