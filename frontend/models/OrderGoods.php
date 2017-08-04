<?php
namespace frontend\models;


use yii\db\ActiveRecord;

class OrderGoods extends ActiveRecord{


    public static function tableName()
    {
        return 'order_goods';
    }
    public function rules()
    {
        return [
            [['order_id','goods_id', 'amount'], 'integer'],
            [['price', 'total'], 'number'],
            [['goods_name', 'logo'], 'string', 'max' => 255],
        ];
    }
}