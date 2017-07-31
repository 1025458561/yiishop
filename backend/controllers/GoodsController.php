<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\SearchForm;
use flyok666\qiniu\Qiniu;
use flyok666\uploadifive\UploadAction;
use yii\web\Request;
use yii\data\Pagination;
class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化搜索对象
        $model = new SearchForm();
        $rs = Goods::find()->where(['status'=>1]);
        //提交表单接收的参数
        $model->search($rs);

        $total = $rs->count();
        //var_dump($total);exit;
        //每页显示条数 3
        $perPage = 3;

        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        $goods = $rs->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['goods'=>$goods,'pager'=>$pager,'model'=>$model]);
    }

    //添加方法
    public  function actionAdd(){
        $model = new Goods();
        $admodel = new GoodsIntro();
        if(!$daymodel=GoodsDayCount::findOne(date('Ymd'))){
            $daymodel = new GoodsDayCount();
        }

        $request = new Request();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $admodel->load($request->post());
            $daymodel->load($request->post());
            //验证数据
            if($model->validate()){
                //执行保存  false 防止验证码验证两次
                $model->create_time = time();
                $model->save(false);
                // var_dump($model->getErrors());exit;

            }else{
                //打印错误信息
               var_dump($model->getErrors());

            }

            //添加day_count表方法
            if($daymodel->validate()){
                $time = date('Ymd');
                $daymodel->day = $time;
                $nu = GoodsDayCount::find()->select('count');
                $count = $nu->count()+1;

                $code = str_pad($count,4,0,STR_PAD_LEFT);
                $model->sn = $daymodel->day.$code;//20170722
                $model->save();

                $daymodel->save();
            }else{
                var_dump($daymodel->getErrors());
            }


                        //添加内容模型
                    if($admodel->validate()){
                $admodel->goods_id = $model->id;
                $admodel->save();
            }else{

            }
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods/index']);
        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->all();
        return $this->render('add',['model'=>$model,'admodel'=>$admodel,'categories'=>$categories]);
    }

    //删除
    public function actionDelete($id){
        $model = Goods::findOne(['id'=>$id]);
        $model->status = 0;
        $model->save();
        return $this->redirect(['goods/index']);
    }

    //修改
    public function actionEdit($id){
        $model = Goods::findOne(['id'=>$id]);

        $admodel = GoodsIntro::findOne(['goods_id'=>$id]);
        if(!$daymodel=GoodsDayCount::findOne(date('Ymd'))){
            $daymodel = new GoodsDayCount();
        }

        $request = new Request();
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            $admodel->load($request->post());
            $daymodel->load($request->post());
            //验证数据
            if($model->validate()){
                //执行保存  false 防止验证码验证两次
                $model->create_time = time();
                $model->save(false);
                // var_dump($model->getErrors());exit;

            }else{
                //打印错误信息
                var_dump($model->getErrors());

            }

            //添加day_count表方法
            if($daymodel->validate()){
                $time = date('Ymd');
                $daymodel->day = $time;
                $nu = GoodsDayCount::find()->select('count');
                $count = $nu->count();
                $daymodel->count = $count;
                $code = str_pad($count,4,0,STR_PAD_LEFT);
                $model->sn = $daymodel->day.$code;//20170722
                $model->save();
                $daymodel->save();
            }else{
                var_dump($daymodel->getErrors());
            }

            //添加内容模型
            if($admodel->validate()){
                $admodel->goods_id = $model->id;
                $admodel->save();
            }else{

            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods/index']);
        }
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->all();
        return $this->render('add',['model'=>$model,'admodel'=>$admodel,'categories'=>$categories]);
    }
///----------------------------------------
//显示内容
    public function actionContent($id){

    }

    //展示相册图片
    public function actionGallery($id)
    {
        $goodsGallerys = GoodsGallery::find()->where(['goods_id'=>$id])->all();

        return $this->render('gallery', [
            'goodsGallerys'=>$goodsGallerys,
                'goods_id'=>$id
            ]);

    }



    //上传文件
    public function actions() {
        return [


            'u-upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias('@webroot')
                ]
            ],


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
                    /*$action->output['fileUrl'] = $action->getWebUrl();
                    //  $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    // $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    // $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    $qiniu = new Qiniu(\Yii::$app->params['qiniu']);
                    $qiniu->uploadFile(
                        $action->getSavePath(), $action->getWebUrl()
                    );
                    $url = $qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $url;*/
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
                },
            ],
        ];

    }
    //保存到七牛云
  /*  public function actionQiniu(){
        $config = [
            'accessKey'=>'lX4u3vgZbhaXpKgV-PytdLqoGpKT1SykXJr4XyjO',
            'secretKey'=>'oaZEfqIDiFV6XX7LyrG64kEI7_ALvpCbDOlp1E9C',
            'domain'=>'http://otby8l75l.bkt.clouddn.com/',
            'bucket'=>'yiishop',
            'area'=>Qiniu::AREA_HUADONG
        ];
    }*/
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }


}
