<?php
namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\PermissionForm;
use backend\models\RoleForm;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller{

    //权限显示首页
    public function actionPermissionIndex(){
        $authManager = \Yii::$app->authManager;
        $model = $authManager->getPermissions();
        return $this->render('permission-index',['model'=>$model]);
    }

    //添加权限
    public function actionPermissionAdd(){
        //实例化对象
        $model = new PermissionForm();
        //定义场景
        $model->scenario = PermissionForm::SCENARIO_ADD;
        //加载模型验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //创建权限
            $authManager = \Yii::$app->authManager;
            $permission = $authManager->createPermission($model->name);
            $permission->description = $model->description;
            //执行添加 保存到数据表
            $authManager->add($permission);
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('permission-index');
        }
        //显示添加页面
        return  $this->render('permission-add',['model'=>$model]);
    }
    //修改权限
    public function actionPermissionEdit($name){
        //检查权限是否存在
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model = new PermissionForm();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //将表单数据重新修改
                $permission->name = $model->name;
                $permission->description =$model->description;
                //更新权限
                $authManager->update($name,$permission);
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect('permission-index');
            }
        }else{
            //回显数据
            $model->name = $permission->name;
            $model->description = $permission->description;
        }
        //展示修改表单数据
        return $this->render('permission-add',['model'=>$model]);
    }
    //删除权限
    public function actionPermissionDelete($name){
        //删除权限
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        $authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('permission-index');
    }

    //角色首页
    public function actionRoleIndex(){
        $authManager = \Yii::$app->authManager;
        $model = $authManager->getRoles();

        return $this->render('role-index',['model'=>$model]);
    }
    //添加角色
    public function actionRoleAdd(){

        //展示页面
        $model = new RoleForm();
        $model->scenario = RoleForm::SCENARIO_ADD;

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $authManager = \Yii::$app->authManager;
          //   var_dump($model);exit;
            //添加内容
            $role = $authManager->createRole($model->name);
            $role ->description = $model->description;

            $authManager->add($role);
           // var_dump($model->permissions);exit;
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
            }
            \Yii::$app->session->setFlash('success','角色添加成功');
            return $this->redirect(['role-index']);
        }
       // echo 333;exit;
        return $this->render('role-add',['model'=>$model]);
    }

    //修改角色

    public function actionRoleEdit($name){
        $model = new RoleForm();
        $authManager = \Yii::$app->authManager;
        $role =  $authManager->getRole($name);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //清空数据关联
               $authManager->removeChildren($role);
            //依次关联
            $role = $authManager->createRole($model->name);
            $role ->description = $model->description;

            $authManager->update($name,$role);
            // var_dump($model->permissions);exit;
            if(is_array($model->permissions)){
                foreach ($model->permissions as $permissionName){
                    $permission = $authManager->getPermission($permissionName);
                    if($permission) $authManager->addChild($role,$permission);
                }
            }
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect('role-index');
        }
        //回显表单数据
        $permissions = $authManager->getPermissionsByRole($name);
        $model->name= $role->name;
        $model->description = $role->description;
        $model->permissions = ArrayHelper::map($permissions,'name','name');
        return $this->render('role-add',['model'=>$model]);
    }

    //删除角色

    public function actionRoleDelete($name){
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if(!$role){
            throw new NotFoundHttpException('角色不存在!');
        }
      /*  $childAll = $authManager->getChildren($role->name);
        //删除角色的权限 遍历角色的权限  逐个删除权限
        if(isset($childAll)){
            foreach ($childAll as $value){
                $permission = $authManager->getPermission($value);
                $authManager->removeChild($permission,$role);
            }
        }*/
        $userIds = $authManager->getUserIdsByRole($name);
        if($userIds){
            throw new NotFoundHttpException('角色被用户绑定不能删除!');
        }
        $authManager->remove($role);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('role-index');
    }
   public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

}