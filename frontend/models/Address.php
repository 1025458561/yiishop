<?php

namespace frontend\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $address
 * @property string $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    public $province;//省
    public $center;//市
    public $area;//区
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','province','center','area','tel','address'],'required'],
            [['status'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['city', 'address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人',
            'city' => '所在地区',
            'address' => '详细地址',
            'tel' => '电话',
            'status' => '设为默认地址',
            'area'=>'省、市、区',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert){
            $province=self::getName($this->province)->name;
            $center=self::getName($this->center)->name;
            $area=self::getName($this->area)->name;
            $this->city=$province.$center.$area;
            $this->user_id=\Yii::$app->user->identity->id;
            if($this->status){
                $this->status=1;
            }else{
                $this->status=0;
            }
        }else{

        }

        return parent::beforeSave($insert);
    }

    //根据id查询省市区的名字
    public static function getName($id){
        $name=Locations::find()->select('name')->where(['id'=>$id])->one();
        return $name;
    }
}