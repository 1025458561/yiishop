<?php
namespace frontend\controllers;


use frontend\components\SphinxClient;
use frontend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller{
    public $layout=false;
    public function actionIndex(){
        $models =GoodsCategory::find()->where(['parent_id'=>0])->all();
      return $this->render('index',['models'=>$models]);
    }

    public function actionTest(){

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
//print_r($cl);
       var_dump($res);
    }
}