<?php
if(Yii::$app->user->can('rbac/add-permission')){
    echo \yii\bootstrap\Html::a('添加权限',['rbac/add-permission'],['class'=>'btn btn-info']);
}
    ?>
<table class="table table-bordered table-hover">
    <tr>
        <th>权限名称</th>
        <th>权限描述</th>
        <th>操作</th>
    </tr>
    <?php foreach ($permissions as $permission):?>
    <tr>
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td>
            <?php
            if(Yii::$app->user->can('rbac/edit-permission')){
                echo  \yii\bootstrap\Html::a('修改',['rbac/edit-permission','name'=>$permission->name],['class'=>'btn btn-xs btn-warning']);
            }
            if(Yii::$app->user->can('rbac/del-permission')){
            echo \yii\bootstrap\Html::a('删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-xs btn-danger']);
            }?>
        </td>
        <?php endforeach;?>
    </tr>
</table>


