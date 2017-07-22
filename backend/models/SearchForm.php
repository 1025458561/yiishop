<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/20
 * Time: 16:24
 */
namespace backend\models;

use yii\base\Model;

class SearchForm extends Model{
    public $k;
    public function rules(){
        return [
          ['k','string','max'=>20]
        ];
    }

}