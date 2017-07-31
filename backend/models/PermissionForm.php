<?php
namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model{
    public $name;
    public $description;
    const SCENARIO_ADD= 'add';

    public function rules()
    {
        return [
          [['name','description'],'required'],
            //权限名不能重复
            ['name','names','on'=>self::SCENARIO_ADD]
        ];
    }
    public function attributeLabels()
    {
        return [
          'name'=>'名称(路由)',
            'description'=>'描述'
        ];
    }
    //自定义方法 权限名不能重复
    public function names(){
    $authManager = \Yii::$app->authManager;
    if($authManager->getPermission($this->name)){
            $this->addError('name','权限已存在');
        }
    }
}