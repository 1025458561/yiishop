<?php

namespace frontend\controllers;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\CaptchaAction;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MemberController extends \yii\web\Controller
{
    public $layout=false;
    //注册用户
    public function actionRegister(){
        //实例化模型
        $model = new Member();
        if(\Yii::$app->request->isPost){
            //加载数据
           // var_dump(\Yii::$app->session->get('code_'.'18881785105'));exit;

            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                //var_dump(\Yii::$app->request->post());exit;
                $model->save(false);
                \yii::$app->session->setFlash('success','注册成功');
                return $this->redirect(['member/login']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        //调用视图
        return $this->render('register',['model'=>$model]);
    }

public function actionIndex(){

        return $this->render('index');
}

    //登录开始
    public function actionLogin()
    {
        $model = new LoginForm();
        //$user = new Member();
        //加载数据
        $cookie = \Yii::$app->request->cookies->get('cart');

        if ($model->load(\Yii::$app->request->post())) {
            //验证数据

            if ($model->validate() && $model->login()) {
                // var_dump($model);exit;
                //1.获取cookie中的购物车数据，
                $cookie = \Yii::$app->request->cookies->get('cart');
                if ($cookie != null) {
                    $carts = unserialize($cookie->value);
                    //2.循环遍历购物车数据
                    foreach ($carts as $goods_id => $amount) {
                        //3.(使用goods_id作为查询条件，member_id)
                        $cart = Cart::findOne(['goods_id' => $goods_id, 'member_id' => \Yii::$app->user->id]);
                        if ($cart) {
                            //4.如果数据表已经有这个商品,就合并cookie中的数量
                            $cart->amount = $cart->amount + $amount;
                            $cart->save(false);
                        } else {
                            //5.如果数据表没有这个商品,就添加这个商品到购物车表
                            $model = new Cart();
                            $model->member_id = \Yii::$app->user->identity['id'];
                            $model->goods_id = $goods_id;
                            $model->amount = $amount;
                            $model->save(false);
                        }
                    }
                    \yii::$app->session->setFlash('success', '登陆成功');
                    return $this->redirect(['index/index']);

                } else {
                    //var_dump($model->getErrors());exit;
                }

            }

        }
        return $this->render('login', ['model' => $model]);
    }
    //添加地址
    public function actionAddress(){
        //实例化模型
        $model = new Address();
        $user_id=\Yii::$app->user->identity->id;
        $address =$model->find()->where(['user_id'=>$user_id])->all();
        $request = new Request();
        //判断提条方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if( $model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{
                //echo 111;
                var_dump($model->getErrors());exit;
            }
        }
        //调用视图，分配数据
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }
    //删除地址
    public function actionDelAddress($id){
        $model=Address::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->delete();
        return $this->redirect(['member/address']);
    }

    //修改地址
    public function actionEditAddress($id){
        //实例化模型
        $model = new Address();
        $user_id=\Yii::$app->user->identity->id;
        $address =$model->find()->where(['user_id'=>$user_id])->all();
        $model=Address::findOne(['id'=>$id]);
        $request = new Request();
        //判断提条方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if( $model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{//验证失败，打印错误信息
                print_r($model->getErrors());exit;
            }
        }
        //调用视图，分配数据
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }

    //设置默认地址
    public function actionChgStatus($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->status==0){
            $model->status=1;
        }
        $model->save();
        return $this->redirect(['member/address']);
    }

    //得到三级联动城市
    public function actionLocations($id){
        $model=new Locations();
        return $model->getProvince($id);
    }
    //地址管理结束

    //定义验证码操作
    public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                //验证码的长度
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

    public function actionLogout(){

        \Yii::$app->user->logout();
        return $this->redirect('/member/login');
    }

    public function actionAbc(){
        var_dump(\Yii::$app->user->isGuest);
    }

    public function actionSms($tel){
        $code = rand(10000,99999);
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$code])->send();
        //将短信验证码保存redis（session，mysql）
        \Yii::$app->session->set('code_'.$tel,$code);

        return json_encode($res);
    }
}