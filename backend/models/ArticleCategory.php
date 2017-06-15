<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $sort
 * @property integer $status
 * @property integer $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    //定义一个静态的状态选项
    static public $statusOptions=['-1'=>'删除','0'=>'隐藏','1'=>'正常'];
    //定义一个静态的状态选项
    static public $is_helpOptions=['0'=>'其他文本','1'=>'普通文本'];
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'intro'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
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
            'sort' => '排序',
            'status' => '状态',
            'is_help' => '类型',
        ];
    }
}
