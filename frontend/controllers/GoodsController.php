<?php
namespace frontend\controllers;


use frontend\models\Goods;
use frontend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller{
    public $enableCsrfValidation = false;
    public $layout = false;
    public function actionIndex($category_id){
        //$id = \Yii::$app->request->get('id','');
       /* if(!$id){
            $models = Goods::find()->limit(7)->all();
        }else{
            $models=Goods::findAll(['goods_category_id'=>$id]);
        }
        return $this->render('list',['models'=>$models]);*/

        $cate = \backend\models\GoodsCategory::findOne(['id'=>$category_id]);
        if($cate->depth == 2){
            $models = Goods::find()->where(['goods_category_id'=>$category_id])->all();
        }else{
            $ids = $cate->leaves()->column();
//var_dump($ids);exit;
            $models = Goods::find()->where(['in', 'goods_category_id', $ids])->all();
        }
        return $this->render('list',['models'=>$models]);
    }

    public function actionContent($id){
        $model=Goods::findOne(['id'=>$id]);
        return $this->render('goods',['model'=>$model]);
    }

}