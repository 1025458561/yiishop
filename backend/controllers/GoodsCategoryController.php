<?php

namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\GoodsCategory;
use yii\web\HttpException;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $goods = GoodsCategory::find()->orderBy('tree,lft')->all();

        return $this->render('index',['goods'=>$goods]);
    }

    //添加分类商品

    public function actionAdd2(){
        //实例化对象
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }
            //跳转到首页
            //显示成提示
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        //显示添加页面
        return $this->render('add2',['model'=>$model]);

    }

//添加数据方法
    public function actionAdd(){
        //实例化对象
        $model = new GoodsCategory(['parent_id'=>0]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否添加一级分类
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                $model->makeRoot();
            }
            //跳转到首页
            //显示成提示
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);
        }
        //显示添加页面
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);

    }

    //修改数据
    public function actionEdit($id){
        //实例化对象
        $model = GoodsCategory::findOne($id);
        //$parent_id = $model->parent_id;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否添加一级分类
            if($model->parent_id==$id){
                //判断不能修改到同级下面
                \Yii::$app->session->setFlash('danger','修改失败,不能修改到自己分类下面');
                return $this->redirect(['goods-category/index']);
            }
            if($model->parent_id){
                //非一级分类
                $category = GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($category){
                    $model->prependTo($category);
                   // var_dump($model);exit;
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }

            }else{
                //一级分类
                if($model->oldAttributes['parent_id'] == 0){
                    $model->save();
                }else{
                    $model->makeRoot();
                }

            }
            //跳转到首页
            //显示成提示
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['goods-category/index']);
        }else{
            \Yii::$app->session->set('false','修改失败');
        }
        //显示修改页面
        $categories = GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return $this->render('add',['model'=>$model,'categories'=>$categories]);

    }


    //删除方法 不能删除有子类的父id
    public function  actionDelete($id){
        $model = GoodsCategory::findOne($id);
        //查找条件为id = parent_id
        $pr = GoodsCategory::find()->where(['parent_id'=>$id]);

        $count = $pr->count();
        //如果parent_id总数大于0 就不能删除
        if($count>0){
            \Yii::$app->session->setFlash('danger','有儿子还在还不能死');
            return $this->redirect(['goods-category/index']);
        }else{
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['goods-category/index']);
        }
    }

    //测试嵌套集合插件方法
    public function actionTest(){
        //创建一个根节点
       /* $category = new GoodsCategory();
        $category->name = '家用电器';
        //$countries->makeRoot();
        $category->makeRoot();*/
        $category2 = new GoodsCategory();
        $category2->name = '智能家电';
        $category = GoodsCategory::findOne(['id'=>1]);
        $category2->parent_id= $category->id;
        $category2->prependTo($category);

        echo '操作完成';
    }

    //测试树状分类
    public function  actionZtree(){
        return $this->renderPartial('ztree');
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
