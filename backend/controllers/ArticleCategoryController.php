<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 20:02
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use yii\web\Controller;
use backend\models\ArticleCategory;
use yii\web\Request;

class ArticleCategoryController extends Controller{
    public function actionIndex(){
        $article =ArticleCategory::find()->where(['status'=>['1','0']])->all();
        // var_dump($brands);exit;
        //显示页面
        return $this->render('index',['article'=>$article]);
    }

    //添加
    public function actionAdd(){
        $model = new ArticleCategory();
        $request = new Request();
        //判断提交方式
        if($request->isPost){
            $model->load($request->post());
            //验证数据
            if($model->validate()){

                $model->save(false);
                // var_dump($model->getErrors());exit;
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        //查询一条id
        $model =ArticleCategory::findOne(['id'=>$id]);
        //把字段值改为-1
        $model->status = -1;
        //保存到数据库
        $model->save();
        //跳转首页
        return $this->redirect(['article-category/index']);
    }

    //修改
    public function actionEdit($id){
        $model = ArticleCategory::findOne($id);
        $request = new Request();
        //判断提交方式
        if($request->isPost){
            $model->load($request->post());
            //验证数据
            if($model->validate()){

                $model->save(false);
                // var_dump($model->getErrors());exit;
                return $this->redirect(['article-category/index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //显示页面
        return $this->render('add',['model'=>$model]);
    }

    //权限
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

}