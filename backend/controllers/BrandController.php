<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:35
 */

namespace backend\controllers;


use yii\web\Controller;

class BrandController extends Controller
{
    public function actionIndex(){
        //实例化对象
        $model = new Brand();
        //显示页面
        return $this->render('index',['model'=>$model]);
    }

}