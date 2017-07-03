<?php

use yii\db\Migration;

/**
 * Handles the creation of table `payment`.
 */
class m170625_035056_create_payment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payment', [
            'id' => $this->primaryKey(),
//delivery_name	varchar	配送方式名称
        'delivery_name'=>$this->string()->notNull()->comment('配送方式名称'),
//delivery_price	float	配送方式价格
        'delivery_price'=>$this->decimal(11,2)->notNull()->comment('配送方式价格'),
            'intro'=>$this->string(255)->notNull()->comment('简介'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('payment');
    }
}
