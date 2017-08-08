<?php
namespace frontend\models;


use frontend\components\SphinxClient;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class IndexSearchForm extends  Model{

    public $name;
    public $sn;
    public $k;
    public function rules(){
        return [
            [['name','string'],'safe'],
            ['sn','string'],
            //double
            ['k','string','max'=>20]
        ];
    }

    //搜索定义属性
    //接收rs传的参数

    public function search(ActiveQuery $rs){
        //加载get提交数据表单
        $this->load(\Yii::$app->request->get());
        if($this->name){
            //andWhere 并行条件
            //$rs->andWhere(['like','name',$this->name]);
            $cl = new SphinxClient() ;
            $cl->SetServer ( '127.0.0.1', 9312);
            //  $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
// $cl->SetMatchMode ( SPH_MATCH_ANY);
            $cl->SetMatchMode ( SPH_MATCH_EXTENDED2);
            //  var_dump($cl);exit;
            $cl->SetLimits(0, 1000);
            $info = '小霸王';
            // var_dump($info);exit;
            $res = $cl->Query($info, 'goods');//shopstore_search
            $ids = ArrayHelper::getColumn($res['matches'],'id');
            $rs->where(['in','id',$ids]);
        }
        if($this->sn){
            $rs->andWhere(['like','sn',$this->sn]);
        }
    }
}