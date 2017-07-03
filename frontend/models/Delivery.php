<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property integer $id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property string $intro
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_name', 'delivery_price', 'intro'], 'required'],
            [['delivery_price'], 'number'],
            [['delivery_name', 'intro'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'intro' => '简介',
        ];
    }
}
