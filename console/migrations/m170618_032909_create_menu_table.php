<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170618_032909_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('菜单名称'),
            'parent_id'=>$this->string(255)->comment('上级菜单'),
            'sort'=>$this->integer()->comment('排序')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
