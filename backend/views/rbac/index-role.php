<?php
if(Yii::$app->user->can('rbac/add-role')){
    echo \yii\bootstrap\Html::a('添加角色',['rbac/add-role'],['class'=>'btn btn-info']);
}
?>
<table class="table table-bordered table-hover">
    <tr>
        <th>角色名称</th>
        <th>角色描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role):?>
    <tr>
        <td><?=$role->name?></td>
        <td><?=$role->description?></td>
        <td><?php
            foreach (Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                echo $permission->description.',';
            }
            ?>
        </td>
        <td>
            <?php
            if(Yii::$app->user->can('rbac/edit-role')){
                echo \yii\bootstrap\Html::a('修改',['rbac/edit-role','name'=>$role->name],['class'=>'btn btn-xs btn-warning']);
            }
            if(Yii::$app->user->can('rbac/edit-role')) {
                echo \yii\bootstrap\Html::a('删除', ['rbac/del-role', 'name' => $role->name], ['class' => 'btn btn-xs btn-danger']);
            }?>
        </td>
        <?php endforeach;?>
    </tr>
</table>
