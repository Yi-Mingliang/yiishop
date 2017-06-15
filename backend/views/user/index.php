<p>
    <?=\yii\bootstrap\Html::a('添加账号',['user/add'],['class'=>'btn btn-success'])?>
    <?=\yii\bootstrap\Html::a('修改密码',['user/password-edit'],['class'=>'btn btn-info'])?>
</p>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>账号</th>
        <th>密码</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user):?>
    <tr>
        <td><?=$user->id?></td>
        <td><?=$user->username?></td>
        <td><?=$user->password_hash?></td>
        <td><?=date('Y-m-d H:i:s',$user->created_at)?></td>
        <td><?=date('Y-m-d H:i:s',$user->updated_at)?></td>
        <td><?=date('Y-m-d H:i:s',$user->last_time)?></td>
        <td><?=$user->last_ip?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['user/edit','id'=>$user->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit'])?>
            <?=\yii\bootstrap\Html::a('删除',['user/del','id'=>$user->id],['class'=>'btn btn-xs  btn-danger glyphicon glyphicon-trash'])?>
        </td>
        <?php endforeach;?>
    </tr>
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

