<?php

use yii\db\Migration;

/**
 * Handles the creation of table `delivery`.
 */
class m170625_035154_create_delivery_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('delivery', [
            'id' => $this->primaryKey(),
//payment_name	varchar	支付方式名称
        'payment_name'=>$this->string()->notNull()->comment('支付方式名称'),
            'intro'=>$this->string(255)->notNull()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('delivery');
    }
}
