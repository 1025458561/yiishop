<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderGoods;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OrderController extends Controller
{
    public $enableCsrfValidation=false;
    public $layout = false;

    //购物车结算页面
    public function actionOrder()
    {
        if (\Yii::$app->user->isGuest) {
            //没登录就跳转到登录的面
            return $this->redirect('/member/login');
        } else {//这是登录操作
            $model = new Order();
            $member_id = \Yii::$app->user->identity->id;
            //获取购物车数据
            //$model->member_id = $member_id;
            $carts = Cart::find()->where(['member_id' => $member_id])->all();
            //定义空数组
            $goods_id = [];
            $amount = [];
            //循环登录用户对应的所有数据
            foreach ($carts as $cart) {
                $goods_id[] = $cart->goods_id;
                $amount[$cart->goods_id] = $cart->amount;
            }
            $goods = Goods::find()->where(['id' => $goods_id])->all();
            //根据用户id获取到地址信息
            $address = Address::find()->where(['user_id' => $member_id])->all();
            //获取到配送方式的名称运费简介
            //var_dump($model);exit;
        }
        //展示订单页面
        return $this->render('order', ['model' => $model, 'address' => $address, 'goods' => $goods, 'amount' => $amount]);
    }

    public function actionOrderAdd()
    {
        //保存数据方法
        // var_dump($delivery_id,$address_id,$pay_id);exit;

        $model = new Order();

        $postdata = \Yii::$app->request->post();
        //var_dump($postdata);exit;

        $model->member_id = \Yii::$app->user->id;
        $model->tel = \Yii::$app->user->identity->tel;
        //城市地区
        $address = Address::findOne(['id' => $postdata['address_id']]);
        $model->name = $address->name;
        $model->province = $address->province;
        $model->city = $address->center;
        $model->area = $address->area;

        //运输方式
        $delivery = Order::$deliveries[$postdata['delivery_id']];
        //var_dump($delivery);exit;
        $model->delivery_id = $postdata['delivery_id'];
        $model->delivery_name = $delivery['name'];
        $model->delivery_price = $delivery['price'];
        //支付方式
        $pay = Order::$pay[$postdata['pay_id']];
        $model->payment_id = $postdata['pay_id'];
        $model->payment_name = $pay['name'];
        $model->status = 1;
        $model->create_time = time();
        if ($model->validate()) {
            $model->save(false);

            //order_goods保存表
            $order_id = $model->id;
            $cart = Cart::findAll(['member_id' => \Yii::$app->user->id]);
            foreach ($cart as $v) {
                $order_goods = new OrderGoods();
                $mode2 = Goods::findOne(['id' => $v->goods_id]);
                if ($mode2->sort >= $v->amount) {
                    $mode2->sort -= $v->amount;
                    $mode2->save(false);
                }
                $order_goods->order_id = $order_id;
                $order_goods->goods_id = $v->goods_id;
                $order_goods->goods_name = $mode2->name;
                $order_goods->logo = $mode2->logo;
                $order_goods->price = $mode2->shop_price;
                $order_goods->amount = $v->amount;
                $order_goods->total = $mode2->shop_price * $v->amount;
                /* $model->total = $v->amount*$v->shop_price;
                 $model->save();*/
                $order_goods->save();
                //清除购物车数据
                 $v->delete();
            }
            return $this->render(['cart/over']);
        }
    }

    public function actionOver(){
        return $this->render('/cart/over');
    }
}
