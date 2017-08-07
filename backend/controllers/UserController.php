<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/25
 * Time: 15:51
 */
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\LoginForm;
use backend\models\User;
use yii\captcha\CaptchaAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;


class UserController extends Controller{
    //首页
    public function actionIndex(){
        $model = User::find();
        $total = $model->count();
        $pagesize = 2;
        //分页工具条
        $pager = new Pagination(
            [
                'totalCount'=>$total,
                'defaultPageSize'=>$pagesize
            ]
        );
        $model = $model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){
        $model = new User();
        $model->scenario=User::SCENARIO_ADD;
        $authManager = \Yii::$app->authManager;
       if($model->load(\Yii::$app->request->post()) && $model->validate()){
           //var_dump($model->Roles);exit;
           $model->save();

             //添加角色
           //$role = $authManager->createRole();
               if(is_array($model->Roles)){
                   foreach ($model->Roles as $role){
                       $roles = $authManager->getRole($role);
                     //  var_dump($roles);exit;
                       if($roles) $authManager->assign($roles,$model->id);
                   }
               }


           \Yii::$app->session->setFlash('success','添加成功');
           return $this->redirect('index');
       }
        return $this->render('add',['model'=>$model]);

    }
    //修改
    public function actionEdit($id){
      $model= User::findOne(['id'=>$id]);
      $authManager = \Yii::$app->authManager;
      //$role = $authManager->getRole();
       // $role =  $authManager->getRole($name);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();

           $authManager->revokeAll($id);
            if(is_array($model->Roles)){
                foreach ($model->Roles as $role){
                    $roles = $authManager->getRole($role);
                    //  var_dump($roles);exit;
                    if($roles) $authManager->assign($roles,$model->id);
                }
            }


            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('index');
        }
        //回显表单数据
       $roles = $authManager->getRolesByUser($id);
        $model->Roles = ArrayHelper::map($roles,'name','name');

        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDelete($id){
        $model = User::findOne(['id'=>$id]);
        $model->delete();
        return $this->redirect('index');
    }
    //验证码
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                'maxLength'=>4,
                'minLength'=>4
            ]
        ];
    }
    //登录方法
    public function actionLogin(){
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate() && $model->login()){
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect('index');
            }
            var_dump($model->getErrors());exit;
        }
      /*  if($model->load(\Yii::$app->request->post()) && $model->login()){

                \Yii::$app->session->setFlash('success','登录成功');
               return $this->redirect('index');

        }else{
            //var_dump($model->getErrors());exit;
        }*/
        return $this->render('login',['model'=>$model]);
    }

    //判断是否游客
    public function actionSet(){
        var_dump(\Yii::$app->user->isGuest);
    }


    //修改密码
    public function actionPersonal(){

        //$models = new User();
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login']);
        }
            $user_id= \Yii::$app->user->identity->getId();
       // var_dump($user_id);exit;
            $model=User::findOne(['id'=>$user_id]);
        //var_dump($model);exit;
            $model->scenario=User::SCENARIO_PERSONAL;
          //var_dump($model->getErrors());exit;

        if($model->load(\Yii::$app->request->post())  && $model->validate()){
            //验证旧密码是否正确
            //var_dump($model);exit;
            if(\Yii::$app->security->validatePassword($model->apassword,$model->password_hash) && $model->apassword!=$model->bpassword){
                //加密密码
              //  var_dump($model);exit;
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->bpassword);
                $model->save();
                \Yii::$app->session->setFlash('success','用户修改成功,请重新登录');
                \Yii::$app->user->logout();
                return $this->redirect(['user/login']);
            }elseif(\Yii::$app->security->validatePassword($model->apassword,$model->password_hash) && $model->bpassword==$model->apassword){
                $model->addError('bpassword','新密码不能和旧密码一样');
            }else{
                $model->addError('apassword','旧密码输入错误');
            }
        }
        return $this->render('personal',['model'=>$model]);
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
               'only'=>['add','delete','edit','index']

            ]
        ];
    }

}