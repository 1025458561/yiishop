<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:22
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord{

    /*public static function tableName()
    {
        return 'brand';
    }*/
    public $imgFile;
    private  $options = [
        '-1'=>'删除','0'=>'隐藏','1'=>'正常'
    ];
    public static function getStatusOptions($hidden_del = true){
        $options = [
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;
    }

    public function rules()
    {
        return [
            //上传文件规则
            [['name','intro','sort','status'],'required','message'=>'{attribute}不能为空'],
         // ['imgFile','file','extensions'=>['jpg','gif','png']]
            ['logo','string']

        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'名字',
            'intro'=>'介绍',
            'sort'=>'排序',
          'imgFile'=>'图片',
            'status'=>'状态'
        ];
    }
    //定义一个只读属性
    public function getSatustext()
    {

        if(array_key_exists($this->status,$this->options)){
            return $this->options[$this->status];
        }
        return '未知';

    }
}