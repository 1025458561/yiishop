<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 14:35
 */

namespace backend\controllers;

use flyok666\qiniu\Qiniu;
use yii\web\Controller;
use backend\models\Brand;
use yii\web\Request;
use yii\web\UploadedFile;
use yii\data\Pagination;
use flyok666\uploadifive\UploadAction;
class BrandController extends Controller
{
    public function actionIndex(){
        //实例化对象
        $rs =Brand::find()->where(['status'=>['1','0']]);
        $total = $rs->count();
        //var_dump($total);exit;
        //每页显示条数 3
        $perPage = 3;
        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
       // var_dump($brands);exit;
        $brands = $rs->limit($pager->limit)->offset($pager->offset)->all();
        //显示页面
        return $this->render('index',['brands'=>$brands,'pager'=>$pager]);
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
            //验证数据
            if($model->validate()){
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
        //显示修改页面
        return $this->render('add',['model'=>$model]);
    }


    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
               // 'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//问价的保存方式

                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                  //  $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                   // $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                   // $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                      $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;
                },
            ],
        ];
    }

    //这是七牛云保存图片方法

        public function actionQiniu(){
            $config = [
                'accessKey'=>'lX4u3vgZbhaXpKgV-PytdLqoGpKT1SykXJr4XyjO',
                'secretKey'=>'oaZEfqIDiFV6XX7LyrG64kEI7_ALvpCbDOlp1E9C',
                'domain'=>'http://otby8l75l.bkt.clouddn.com/',
                'bucket'=>'yiishop',
                'area'=>Qiniu::AREA_HUADONG
            ];


        $qiniu = new Qiniu($config);
        $key = 'upload/1c/1d/1c1db0cc5878244f819c02406b5ffcd6bdb8009a.jpg';
        //将图片上传到七牛云
        $qiniu->uploadFile(
            \Yii::getAlias('@webroot').'/upload/1c/1d/1c1db0cc5878244f819c02406b5ffcd6bdb8009a.jpg',
            $key);
        //获取图片地址
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}