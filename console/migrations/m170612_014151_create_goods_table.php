<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_014151_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
//            name varchar﴿02﴾ 商品名称
        'name'=>$this->string(20)->comment('商品名称'),
//sn varchar﴿02﴾ 货号
        'sn'=>$this->string(20)->comment('货号'),
//logo varchar﴿552﴾ LOGO图片
        'logo'=>$this->string(255)->comment('LOGO图片'),
//goods_category_id int 商品分类id
        'goods_category_id'=>$this->integer()->comment('商品分类id'),
//brand_id int 品牌分类
        'brand_id'=>$this->integer()->comment('品牌分类'),
//market_price decimal﴿2,01﴾ 市场价格
        'market_price'=>$this->decimal(10,2)->comment('市场价格'),
//shop_price decimal﴿2 ,01﴾ 商品价格
        'shop_price'=>$this->decimal('10',2)->comment('商品价格'),
//stock int 库存
        'stock'=>$this->integer()->comment('库存'),
//is_on_sale int﴿1﴾ 是否在售﴿架下0 售在1﴾
        'is_on_sale'=>$this->integer()->comment('是否在售'),
//status inter﴿1﴾ 状态﴿站收回0 常正1﴾
        'status'=>$this->integer()->comment('状态'),
//sort int﴿﴾ 排序
        'sort'=>$this->integer()->comment('排序'),
//create_time int﴿﴾ 添加时间
        'create_time'=>$this->integer()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
