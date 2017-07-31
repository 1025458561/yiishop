<?php
namespace backend\controllers;

use backend\filters\RbacFilter;
use backend\models\Article;
use backend\models\ArticleDetail;
use backend\models\SearchForm;
use yii\web\Controller;
use yii\web\Request;
use yii\data\Pagination;
class ArticleController extends Controller{
    public function actionIndex(){
        //实例化搜索对象
        $search = new SearchForm();
        $request = new Request();
        if($request->isGet){
            $search->load($request->get());
            $a = $search->k;

            if($a){
                //搜索对象规则 保存到变量
                $key = " name like '%$a%' and ";
            }else{
                $key= '';
            }
        }
        //搜索where条件连接上搜索条件
        $rs = Article::find()->where($key.'status>=0')->orderBy('sort desc');
        //显示首页
        $total = $rs->count();
        //var_dump($total);exit;
        //每页显示条数 3
        $perPage = 3;

        //分页工具类
        $pager = new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$perPage
        ]);
        $article = $rs->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['article'=>$article,'pager'=>$pager,'search'=>$search]);
    }

    //添加
    public function actionAdd(){
        //实例化对象
        $model = new Article();
        $admodel = new ArticleDetail();

        $request = new Request();
        if($request->isPost){
        //加载数据
            $model->load($request->post());

            $res = $admodel->load($request->post());
            //var_dump($res,$admodel);exit;
            //验证数据
            if($model->validate()){
                //保存数据 article
                //添加时间
                $model->create_time = time();
                $model->save();

            }else{
                var_dump($model->getErrors());exit;
            }
            if($admodel->validate()){
              //  var_dump($admodel);exit;
                $admodel->article_id = $model->id;

                $admodel->save();
                return $this->redirect('/article/index');

            }else{
                var_dump($admodel->getErrors());exit;
            }

        }
        //显示添加页面
        return $this->render('add',['model'=>$model,'admodel'=>$admodel]);
        //return $this->render('add',['models'=>$models]);
    }
//删除
public function actionDelete($id){
        $article = Article::findOne($id);

        $article->status = -1;
        $article->save();
        return $this->redirect(['article/index']);
}
//修改
public function  actionEdit($id)
{
    //实例化对象
        $model = Article::findOne($id);

        $admodel = ArticleDetail::findOne($id);

        $request = new Request();
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());

            $res = $admodel->load($request->post());
            //var_dump($res,$admodel);exit;
            //验证数据
            if ($model->validate()) {
                //保存数据 article
                $model->create_time = time();
                $model->save();

            } else {
                var_dump($model->getErrors());
                exit;
            }
            if ($admodel->validate()) {
                //  var_dump($admodel);exit;
                $admodel->article_id = $model->id;

                $admodel->save();
                return $this->redirect('/article/index');

            } else {
                var_dump($admodel->getErrors());
                exit;
            }

        }
        //显示添加页面
        return $this->render('add', ['model' => $model, 'admodel' => $admodel]);
        //return $this->render('add',['models'=>$models]);
    }
    public function actions()
    {
        return [
            'u-upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://admin.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias('@webroot')
                ]
            ]
        ];
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