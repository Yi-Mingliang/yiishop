<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170608_115500_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            //name varchar﴿05﴾ 名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
//intro text 简介
            'intro'=>$this->text()->notNull()->comment('简介'),
//sort int﴿11﴾ 排序
            'sort'=>$this->integer(11)->comment('排序'),
//status int﴿2﴾ 状态﴿常正1 藏隐0 除删1‐﴾
            'status'=>$this->smallInteger(2)->comment('状态'),
//is_help int﴿1﴾ 类型
            'is_help'=>$this->smallInteger(1)->comment('类型'),
        ],'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
