<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class ApiController extends Controller{
    //Josn
    public function init()
    {
        parent::init();
        \Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public $enableCsrfValidation=false;
    //会员注册
    public function actionRegister(){
        if(\Yii::$app->request->isPost){
            $model = new Member();
            $model->username = \Yii::$app->request->post('username');
            $model->password = \Yii::$app->request->post('password');
            $model->tel = \Yii::$app->request->post('tel');
            $model->email = \Yii::$app->request->post('email');
            if($model->validate()){
                //成功
                $model->save();
                $rs = [
                    'errorCode'=>2,
                    'errorMsg'=>'注册成功',
                    'data'=>[]
                ];
            }else{
                //验证不通过
                $rs = [
                    'errorCode'=>0,
                    'errorMsg'=>'注册失败',
                    'data'=>$model->getErrors()
                ];
            }

        }else{
            $rs = [
              'errorCode'=>1,
                'errorMsg'=>'请求方式错误,请重新提交数据',
                'data'=>[]
            ];
        }
        return $rs;
    }

    //登录功能
      public function actionLogin(){
          $model = new LoginForm();

          if (\Yii::$app->request->isPost) {
              //加载
              $model->username = \Yii::$app->request->post('username');
              $model->password = \Yii::$app->request->post('password');
              if ($model->validate() && $model->login()) {
                  //验证数据
                  $rs = [
                      'errorCode'=>2,
                      'errorMsg'=>'登录成功',
                      'data'=>[]
                  ];
                  }else{
                  $rs = [
                      'errorCode'=>0,
                      'errorMsg'=>'登录失败',
                      'data'=>$model->getErrors()
                     ];
                  }

              }else{

              $rs = [
                  'errorCode'=>1,
                  'errorMsg'=>'请求方式错误,请重新提交数据',
                  'data'=>[]
              ];
          }
          return $rs;
      }

      //地址添加
       public function  actionAddress(){
           $model = new Address();
           $request = new Request();
           //判断提条方式
           if($request->isPost){
               //加载数据
               $model->name = \Yii::$app->request->post('name');
               $model->province = \Yii::$app->request->post('province');
               $model->center = \Yii::$app->request->post('center');
               $model->area = \Yii::$app->request->post('area');
               $model->address = \Yii::$app->request->post('address');
               $model->tel = \Yii::$app->request->post('tel');
               if( $model->validate()){
                   $model->save();
                   $rs = [
                       'errorCode'=>2,
                       'errorMsg'=>'添加成功',
                       'data'=>[]
                   ];
               }else{
                   $rs = [
                       'errorCode'=>0,
                       'errorMsg'=>'添加失败',
                       'data'=>$model->getErrors()
                   ];
               }
           }else{
               $rs = [
                   'errorCode'=>1,
                   'errorMsg'=>'请求方式错误,请重新提交数据',
                   'data'=>[]
               ];
           }
           //调用视图，分配数据
           return $rs;
       }

    //地址修改
    public function actionEditAddress(){
        //实例化模型
        $model = new Address();
       // $user_id=\Yii::$app->user->identity->id;
        //$address =$model->find()->where(['user_id'=>$user_id])->all();
        $model=Address::findOne(['id'=>2]);
        //判断提条方式
        $request=new Request();
        if($request->isPost){
            //加载数据
            $model->name = \Yii::$app->request->post('name');
            $model->province = \Yii::$app->request->post('province');
            $model->center = \Yii::$app->request->post('center');
            $model->area = \Yii::$app->request->post('area');
            $model->address = \Yii::$app->request->post('address');
            $model->tel = \Yii::$app->request->post('tel');
            if( $model->validate()){
                $model->save();
                $rs = [
                    'errorCode'=>2,
                    'errorMsg'=>'修改成功',
                    'data'=>[]
                ];
            }else{//验证失败，打印错误信息
                $rs = [
                    'errorCode'=>0,
                    'errorMsg'=>'修改失败',
                    'data'=>$model->getErrors()
                ];
            }
        }else{
            $rs = [
                'errorCode'=>1,
                'errorMsg'=>'请求方式错误,请重新提交数据',
                'data'=>[]
            ];
        }
        //调用视图，分配数据
        return $rs;
    }

    //地址删除
    public function actionDeleteAddress(){
        $model=Address::findOne(['id'=>2]);
        if($model==null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->delete();
        $rs = [
            'errorCode'=>2,
            'errorMsg'=>'删除成功',
            'data'=>[]
        ];
        return $rs;
    }
}