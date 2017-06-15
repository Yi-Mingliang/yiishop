<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    //定义一个logo的字段
    //public $imgFile;
    //状态的选项
    static public $statusOptions=['-1'=>'删除','0'=>'隐藏','1'=>'正常'];
//    //设置场景变量
//    const SCENARIO_ADD ='add';
//    const SCENARIO_EDIT ='edit';
//    //定义场景字段
//    public function scenarios()
//    {
//       $scenarios= parent::scenarios();
//        $scenarios[self::SCENARIO_ADD]=[ 'name','imgFile', 'intro', 'sort','status'];
//        $scenarios[self::SCENARIO_EDIT]=[ 'name','imgFile', 'intro', 'sort','status'];
//        return $scenarios;
//    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' =>250],
            //['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true,'on'=>self::SCENARIO_EDIT],
            //['imgFile','file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>false,'on'=>self::SCENARIO_ADD],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
            'imgFile'=>'logo'
        ];
    }
}
