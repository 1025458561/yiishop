<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 6:15
 */
namespace backend\models;


use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord{

    public static function tableName()
    {
        return 'article_category';
    }
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
        ];
    }
    public function attributeLabels()
    {
        return [
            'name'=>'名字',
            'intro'=>'介绍',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

}