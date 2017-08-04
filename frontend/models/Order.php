<?php
namespace frontend\models;


use yii\db\ActiveRecord;

class Order extends ActiveRecord{

    //这是定义送货方式
public static $deliveries = [
    '1'=>['id'=>'1','name'=>'顺丰快递','price'=>'20','detail'=>'价格贵'],
    '2'=>['id'=>'2','name'=>'圆通快递','price'=>'10','detail'=>'速度快'],
    '3'=>['id'=>'3','name'=>'EMS','price'=>'10','detail'=>'服务好'],
];
//定义支付方式
public static $pay = [
    '1'=>['id'=>'1','name'=>'货到付款','detail'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
    '2'=>['id'=>'2','name'=>'在线支付','detail'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
    '3'=>['id'=>'3','name'=>'上门自提','detail'=>'自提时付款，支持现金、POS刷卡、支票支付'],
    '4'=>['id'=>'4','name'=>'邮局汇款','detail'=>'通过快钱平台收款 汇款后1-3个工作日到账'],
];
    public function rules()
    {
        return [
            [['member_id', 'tel', 'delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name','payment_name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_name', 'trade_no'], 'string', 'max' => 255],
        ];
    }

}


