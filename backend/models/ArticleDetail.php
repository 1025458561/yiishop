<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 13:13
 */
namespace backend\models;

use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord{
    //public $content;
    public static function  tableName()
    {
        return 'article_detail';
    }
    public function rules()
    {
       return [
         [['content'],'required']
       ];
    }

}