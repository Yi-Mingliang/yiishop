<p><?=\yii\bootstrap\Html::a('添加文章类型',['article/add'],['class'=>'btn btn-success'])?></p>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章类别</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->articleCategory->name?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Article::$statusOptions[$model->status]?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
            <td><?=\yii\bootstrap\Html::a('',['article/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit'])?>
                <?=\yii\bootstrap\Html::a('',['article/del','id'=>$model->id],['class'=>'btn btn-xs  btn-danger glyphicon glyphicon-trash'])?>
                <?=\yii\bootstrap\Html::a('查看文章详情',['article/detail','id'=>$model->id],['class'=>'btn btn-xs  btn-info'])?>
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