<p><?=\yii\bootstrap\Html::a('添加文章类型',['goods-category/add'],['class'=>'btn btn-success'])?></p>
<table class="list table table-bordered table-hover"  >
    <thead>
        <tr>
            <th>ID</th>
            <th>名称</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody id="content">
    <?php foreach ($models as $model):?>
        <tr data-tree="<?=$model->tree?>" data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>">
            <td><?=$model->id?></td>
            <td>
                <?=str_repeat(' — ',$model->depth).$model->name?>
                <span class="glyphicon glyphicon-chevron-down expend" style="float: right"></span>
            </td>
            <td><?=\yii\bootstrap\Html::a('',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit'])?>
                &emsp;<?=\yii\bootstrap\Html::a('',['goods-category/del','id'=>$model->id],['class'=>'btn btn-xs  btn-danger glyphicon glyphicon-trash'])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
$js= <<<js
    //绑定事件
    $('.expend').click(function() {
        //改变箭头方向
       var show = $(this).hasClass("glyphicon-chevron-up");//判断箭头方向是否向上
        $(this).toggleClass('glyphicon glyphicon-chevron-down');
        $(this).toggleClass('glyphicon glyphicon-chevron-up');
       //获取当前节点的父节点 tr
       var parent_tr=$(this).closest('tr');
       //获取当前父节点的左右值 以及树值
       var parent_lft=parseInt(parent_tr.attr('data-lft'));
       var parent_rgt=parseInt(parent_tr.attr('data-rgt'));
       var parent_tree=parseInt(parent_tr.attr('data-tree'));
       //遍历所有的tr
       $('#content tr').each(function() {
            //获取当前tr的左右值 以及树值
             var lft=parseInt($(this).attr('data-lft'));
       var rgt=parseInt($(this).attr('data-rgt'));
       var tree=parseInt($(this).attr('data-tree'));
  
       if(tree== parent_tree && lft>parent_lft && rgt<parent_rgt){
           show?$(this).fadeIn():$(this).fadeOut();
           console.log(show);
       }
       })
       
       
        
    })
js;
$this->registerJs($js);
?>



