<p><?php
    if(Yii::$app->user->can('article_detail/add')){
        echo  \yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-success']);
    }
   ?></p>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>Logo</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <?php if($model->status==-1){
            continue;
        }?>
     <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?php if ($model->logo){?>
            <img src="<?=$model->logo?>" width="80px"/>
            <?php }?>
        </td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=\backend\models\Brand::$statusOptions[$model->status]?></td>
        <td>
            <?php
            if(Yii::$app->user->can('brand/edit')){
                echo  \yii\bootstrap\Html::a('',['brand/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit']);
            }
            if(Yii::$app->user->can('brand/del')) {
                echo \yii\bootstrap\Html::a('', ['brand/del', 'id' => $model->id], ['class' => 'btn btn-xs  btn-danger glyphicon glyphicon-trash']);
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
