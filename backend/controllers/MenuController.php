<?php
namespace backend\controllers;


use backend\filters\RbacFilter;
use backend\models\Menu;
use yii\data\Pagination;
use yii\web\Controller;

class MenuController extends Controller{
    //展示首页
    public function actionIndex(){
        $rs = Menu::find();
        $total = $rs->count();
        //var_dump($total);exit;
        //每页显示条数 3
        $perPage = 6;
        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        // var_dump($brands);exit;
        $model = $rs->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['model'=>$model,'pager'=>$pager]);
    }
    //添加
    public function actionAdd(){

        $model = new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断顶级分类
            if(!$model->parent_id){
                $model->parent_id=0;
            }
            $model->create_time= time();
            $model->save();
           \Yii::$app->session->setFlash('success','添加成功');
          return  $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model = Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if($model->parent_id && !empty($model->children)){
                $model->addError('parent_id','只能为顶级菜单');
            }else{
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return  $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDelete($id){
        $model =Menu::findOne(['id'=>$id]);
        $rs = Menu::find()->where(['parent_id'=>$id]);
        $count = $rs->count();
        if($count>0){
            \Yii::$app->session->setFlash('danger','不能删除，有子分类');
            return $this->redirect('index');
        }
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect('index');
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