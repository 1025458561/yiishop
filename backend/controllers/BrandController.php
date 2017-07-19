<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:35
 */

namespace backend\controllers;


use yii\web\Controller;
use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    public function actionIndex(){
        //实例化对象
        $brands =Brand::find()->where(['status'=>['1','0']])->all();
       // var_dump($brands);exit;
        //显示页面
        return $this->render('index',['brands'=>$brands]);
    }
    //添加方法
    public function actionAdd(){
        //实例化对象
        $model = new Brand();
        $request = new Request();
        //判断提交方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
          //  var_dump($model);exit;
            //验证数据
            if($model->validate()){
                //如果有文件上传就处理图片
                if($model->imgFile){
                    //添加存放文件夹路径
                    $d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        //没有这个文件就创建
                        mkdir($d);
                    }
                    //拼接图片路径
                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //移动图片
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    //将图片存到图片属性中

                    $model->logo=$filename;

                // var_dump($model->imgFile);exit;
                }
                //执行保存  false 防止验证码验证两次
                $model->save(false);
               // var_dump($model->getErrors());exit;
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        //显示添加页面
        return $this->render('add',['model'=>$model]);
    }

    //删除功能
    public function actionDelete($id){
        //查询一条id
        $model = Brand::findOne(['id'=>$id]);
        //把字段值改为-1
        $model->status = -1;
        //保存到数据库
        $model->save();
        //跳转首页
        return $this->redirect(['brand/index']);
    }

    //修改功能
    public function actionEdit($id){
        $model = Brand::findOne($id);
        $request = new Request();
        //判断提交方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //实例化上传文件对象
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');
            //  var_dump($model);exit;
            //验证数据
            if($model->validate()){
                //如果有文件上传就处理图片
                if($model->imgFile){
                    //添加存放文件夹路径
                    $d=\yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($d)){
                        //没有这个文件就创建
                        mkdir($d);
                    }
                    //拼接图片路径
                    $filename = '/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    //移动图片
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$filename,false);
                    //将图片存到图片属性中

                    $model->logo=$filename;

                    // var_dump($model->imgFile);exit;
                }
                //执行保存  false 防止验证码验证两次
                $model->save(false);
                // var_dump($model->getErrors());exit;
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());exit;
            }
        }
        //显示添加页面
        return $this->render('add',['model'=>$model]);
    }
}