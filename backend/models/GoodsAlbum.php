<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_album".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $path
 */
class GoodsAlbum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_album';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'path' => '图片保存路径',
        ];
    }
}
