<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170624_130557_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            //商品id
            'goods_id'=>$this->integer()->notNull()->comment('商品id'),
            //用户id
            'member_id'=>$this->integer()->notNull()->comment('用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
