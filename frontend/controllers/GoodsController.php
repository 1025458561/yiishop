<?php
namespace frontend\controllers;


use frontend\models\Goods;
use frontend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsController extends Controller{
    public $enableCsrfValidation = false;
    public $layout = false;
    public function actionIndex($category_id){
        //$id = \Yii::$app->request->get('id','');
       /* if(!$id){
            $models = Goods::find()->limit(7)->all();
        }else{
            $models=Goods::findAll(['goods_category_id'=>$id]);
        }
        return $this->render('list',['models'=>$models]);*/

        $cate = \backend\models\GoodsCategory::findOne(['id'=>$category_id]);
        if($cate->depth == 2){
            $models = Goods::find()->where(['goods_category_id'=>$category_id])->all();
        }else{
            $ids = $cate->leaves()->column();
//var_dump($ids);exit;
            $models = Goods::find()->where(['in', 'goods_category_id', $ids])->all();
        }
        return $this->render('list',['models'=>$models]);
    }

    public function actionContent($id){
        $model=Goods::findOne(['id'=>$id]);
        return $this->render('goods',['model'=>$model]);
    }


   /* //
    public function actionAddToCart()
    {
        //未登录
        if(\Yii::$app->user->isGuest){
            $goods_id=\Yii::$app->request->post('goods_id');
            $amount=\Yii::$app->request->post('amount');
            //如果没有登录就存放在cookie中
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，数量累加
                    $carts[$goods_id] += $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);

        }else{
            //用户已登录，操作购物车数据表
        }
        return $this->redirect(['cart']);
    }


    //展示页面
    public function actionCart()
    {
        $this->layout = false;
        //1 用户未登录，购物车数据从cookie取出
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            //var_dump(unserialize($cookies->getValue('cart')));
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [];
            }else{
                $carts = unserialize($cart->value);
            }

            $models = Goods::find()->where(['in','id',array_keys($carts)])->all();
        }else{

        }
        return $this->render('cart',['models'=>$models,'carts'=>$carts]);
    }
    public function actionAjaxCart()
    {
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //数据验证
        if(\Yii::$app->user->isGuest){
            $cookies = \Yii::$app->request->cookies;
            //获取cookie中的购物车数据
            $cart = $cookies->get('cart');
            if($cart==null){
                $carts = [$goods_id=>$amount];
            }else{
                $carts = unserialize($cart->value);//[1=>99，2=》1]
                if(isset($carts[$goods_id])){
                    //购物车中已经有该商品，更新数量
                    $carts[$goods_id] = $amount;
                }else{
                    //购物车中没有该商品
                    $carts[$goods_id] = $amount;
                }
            }
            //将商品id和商品数量写入cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                'name'=>'cart',
                'value'=>serialize($carts),
                'expire'=>7*24*3600+time()
            ]);
            $cookies->add($cookie);
            return 'success';
        }
    }*/
}