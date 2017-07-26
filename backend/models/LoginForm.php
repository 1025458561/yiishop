<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/18
 * Time: 8:13
 */
namespace backend\models;

use backend\models\User;
use yii\base\Model;

class LoginForm extends Model{
    public $code;
    public $username;
    public $password;
    public $rember;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['rember','boolean'],
            ['code','captcha','captchaAction'=>'user/captcha']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rember'=>'记住我'
        ];
    }

    public function login()
    {
       // $isRemeber=1;
        //1.1 通过用户名查找用户
        $user = \backend\models\User::findOne(['username'=>$this->username]);
       // var_dump($user);exit;
        if($user){
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //密码正确.可以登录
                //2 登录(保存用户信息到session)
               // $duration = $isRemeber? 0:3600;
               // var_dump($this->password_hash);exit;
               \Yii::$app->user->login($user,$this->rember?3600*24*7:0);

                $user->last_login_time = time();
                $user->last_login_ip=$_SERVER['REMOTE_ADDR'];
                $user->save(false);
                //var_dump($user);exit;
               return true;
            }else{
                //密码错误.提示错误信息
                $this->addError('password_hash','密码错误');
            }

        }else{
            //用户不存在,提示 用户不存在 错误信息
            $this->addError('username','用户名不存在');
        }
        return false;
    }


}