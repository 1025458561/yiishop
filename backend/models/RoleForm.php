<?php
namespace backend\models;

use yii\base\Model;

class RoleForm extends Model{
public $name;
public $description;
public $permissions=[];
const SCENARIO_ADD = 'add';
public function rules()
{
    return [
      [['name','description'],'required'],
        ['name','names','on'=>self::SCENARIO_ADD],
        ['permissions','safe']
    ];
}
    public function attributeLabels()
    {
        return [
            'name'=>'名字',
            'description'=>'描述',
            'permissions'=>'添加权限'
        ];
    }
    public function names(){
    $authManager = \Yii::$app->authManager;
    if($authManager->getRole($this->name)){
            $this->addError('name','管理员已存在');
     }
    }
}