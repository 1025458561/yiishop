<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 12:42
 */
namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord{
    public $k;

    public function rules()
    {
        return[
        [['name','article_category_id','sort','status','intro'],'required','message'=>'{attribute}不能为空']
    ];

    }

    public function attributeLabels()
    {
       return [
          'name'=>'名字',
           'intro'=>'简介',
           'article_category_id'=>'文章分类id',
            'content'=>'内容',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

    public static function getStatusOptions($hidden_del = true){
        $status = [
          '-1'=>'删除',
            '0'=>'隐藏',
            '1'=>'正常'
        ];
        if($hidden_del){
            unset($status['-1']);
        }
        return $status;
    }
    //分类id方法
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
       // return $this->hasOne(ArticleDetail::className(),['id'='']);
    }
    //内容关联方法
    public function getArticleDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }
}