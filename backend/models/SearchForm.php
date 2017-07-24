<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/20
 * Time: 16:24
 */
namespace backend\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class SearchForm extends Model{
    public $name;
    public $sn;
    public $k;
    public function rules(){
        return [
            ['name','string','max'=>50],
             ['sn','string'],
            //double
          ['k','string','max'=>20]
        ];
    }

    //搜索定义属性
    //接收rs传的参数
    //ActiveQuery 查询接收参数的值
    public function search(ActiveQuery $rs){
        //加载get提交数据表单
        $this->load(\Yii::$app->request->get());
        if($this->name){
            //andWhere 并行条件
            $rs->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $rs->andWhere(['like','sn',$this->sn]);
        }
    }
}