<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/20
 * Time: 15:58
 */
namespace backend\controllers;

use backend\models\ArticleDetail;
use yii\web\Controller;
use yii\data\Pagination;
class ArticleDetailController extends Controller{
    public function actionIndex(){
        $rs = ArticleDetail::find();
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
        return $this->render('index',['article'=>$article,'pager'=>$pager]);
    }

}