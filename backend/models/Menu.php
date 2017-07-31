<?php
namespace  backend\models;


use function PHPSTORM_META\map;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord{
    public function rules()
    {
        return [
          [['menu_name','parent_id','menu_url'],'safe']
        ];
    }
    public function attributeLabels()
    {
        return [
          'menu_name'=>'名称',
           'menu_url'=>'路由',
            'parent_id'=>'上级菜单'
        ];
    }
   /* public static function getmenu(){
        $array = ArrayHelper::map(Menu::find()->where('parent_id=0')->all(),'id','menu_name');
        $array[0] = '顶级分类';

        return $array;
    }*/
public function getChildren(){
    return $this->hasMany(self::className(),['parent_id'=>'id']);
}

}