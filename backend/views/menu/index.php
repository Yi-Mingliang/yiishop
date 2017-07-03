<?php
if(Yii::$app->user->can('menu/add')){
    echo \yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-success']);
}
?>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>菜单名称</th>
        <th>上一级菜单</th>
        <th>路由/地址</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=($model->parent_id)?$model->parent->name:'顶级菜单';?></td>
        <td><?=$model->url?></td>
        <td><?=$model->sort?></td>
        <td><?php
            if(Yii::$app->user->can('menu/edit')){
                echo \yii\bootstrap\Html::a('',['menu/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit']);
            }
            if(Yii::$app->user->can('menu/del')) {
                echo \yii\bootstrap\Html::a('', ['menu/del', 'id' => $model->id], ['class' => 'btn btn-xs  btn-danger glyphicon glyphicon-trash']);
            }?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
    'firstPageLabel' => '首页',
    'lastPageLabel' => '尾页',
    'options'=>['class'=>'pagination','style'=>'padding-left:36%'],
]);
?>
