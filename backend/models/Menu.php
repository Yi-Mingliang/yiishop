<?php

namespace backend\models;

use Yii;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property string $name
 * @property string $parent_id
 * @property integer $sort
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','parent_id'], 'required'],
            [['sort'], 'integer'],
//            ['name','unique','message'=>'该菜单名称已存在'],
            [['name'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 255],
            [['parent_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'sort' => '排序',
            'url' => '路由/地址',
        ];
    }


    //定义一个get方法   建立与上一级的关系连接  1对1
    public function getParent(){
        return $this->hasOne(self::className(),['id'=>'parent_id']);
    }


    public function addMenu(){
        $menu=self::findOne($this->name);
        if($menu){
            $this->addError('name','该菜单已存在');
            return false;
        }else{
            return true;
        }
    }

    public function editMenu(){
        $menu=self::findOne(['id'=>$this->id]);
        if($menu->name!=$this->name && self::findOne(['name'=>$this->name])){
           $this->addError('name','该菜单名称已存在');
        }else{
            return true;
        }
    }

    public function delMenu(){
        $menu=self::findOne(['parent_id'=>$this->id]);
        if($menu){
            throw new NotFoundHttpException('该菜单名称下有子菜单，不能删除');
        }else{
            return true;
        }
    }

    //定义一个get方法  建立与二级菜单的关系  1对多
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
