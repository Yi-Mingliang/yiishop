<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170608_071938_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
//name varchar﴿05﴾ 名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
//intro text 简介
            'intro'=>$this->text()->notNull()->comment('简介'),
//logo varchar﴿552﴾ LOGO图片
            'logo'=>$this->string(200)->comment('LOGO'),
//sort int﴿11﴾ 排序
            'sort'=>$this->integer(11)->comment('排序'),
//status int﴿2﴾ 状态﴿常正1 藏隐0 除删1‐﴾
            'status'=>$this->smallInteger(2)->comment('状态'),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');




    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
