<?php if (Yii::$app->user->can('article_category/add')){
    echo \yii\bootstrap\Html::a('添加文章类型',['article_category/add'],['class'=>'btn btn-success']);
}
?>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\Article_Category::$statusOptions[$model->status]?></td>
        <td><?=\backend\models\Article_Category::$is_helpOptions[$model->is_help]?></td>
        <td>
            <?php
            if(Yii::$app->user->can('article_category/edit')){
                echo \yii\bootstrap\Html::a('',['article_category/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit']);
            }
            if(Yii::$app->user->can('article_category/del')){
                echo \yii\bootstrap\Html::a('',['article_category/del','id'=>$model->id],['class'=>'btn btn-xs  btn-danger glyphicon glyphicon-trash']);
            }
            ?>
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