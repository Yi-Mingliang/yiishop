<p><?=\yii\bootstrap\Html::a('添加文章类型',['article_detail/add'],['class'=>'btn btn-success'])?></p>
<table class="table table-bordered table-hover">
    <tr>
        <th>文章名</th>
        <th>内容</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->article->name?></td>
            <td><?=$model->content?></td>
            <td><?=\yii\bootstrap\Html::a('',['article_detail/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit'])?>
                <?=\yii\bootstrap\Html::a('',['article_detail/del','id'=>$model->id],['class'=>'btn btn-xs  btn-danger glyphicon glyphicon-trash'])?>
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