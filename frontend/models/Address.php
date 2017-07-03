<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property integer $member_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $phone
 * @property string $detail_address
 */
class Address extends \yii\db\ActiveRecord
{
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
            [['member_id', 'province_id', 'city_id', 'area_id'], 'integer'],
            [['name','phone','detail_address'],'required','message'=>'{attribute}不能为空'],
            [['phone'], 'string', 'max' => 11],
            [['detail_address'], 'string', 'max' => 255],
            ['status','boolean']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'province_id' => '所在区地址',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'phone' => '手机号码',
            'detail_address' => '详细地址',
            'status'=>'设为默认地址',
            'name'=>'收货人'
        ];
    }

    public function getProvince(){
        return $this->hasOne(Locations::className(),['id'=>'province_id']);
    }
    public function getCity(){
        return $this->hasOne(Locations::className(),['id'=>'city_id']);
    }
    public function getArea(){
        return $this->hasOne(Locations::className(),['id'=>'area_id']);
    }

}
