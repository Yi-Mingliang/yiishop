<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_album`.
 */
class m170614_025616_create_goods_album_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_album', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'path'=>$this->string()->comment('图片保存路径')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_album');
    }
}
