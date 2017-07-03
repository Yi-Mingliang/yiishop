<?php
if(Yii::$app->user->can('goods/add')){
    echo \yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-success']);
}
?>
<?php
    $form=\yii\bootstrap\ActiveForm::begin([
            'method'=>'get',
            'action'=>\yii\helpers\Url::to(['goods/index']),
            'options'=>['class'=>'form-inline','style'=>'float:right']
            ]
    );
    echo $form->field($goodsSearch,'name')->textInput(['placeholder'=>'商品名称'])->label(false);
    echo $form->field($goodsSearch,'sn')->textInput(['placeholder'=>'货号'])->label(false);
    echo $form->field($goodsSearch,'minPrice')->textInput(['placeholder'=>'￥'])->label(false);
    echo $form->field($goodsSearch,'maxPrice')->textInput(['placeholder'=>'￥'])->label(' — ');
    echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
?>



<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>LOGO图片</th>
        <th>商品类别</th>
        <th>品牌分类</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>是否在售</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->sn?></td>
        <td><img src="<?=Yii::getAlias('@web').$model->logo?>"/></td>
        <td><?=$model->goodsCategory->name?></td>
        <td><?=$model->brand->name?></td>
        <td><?=$model->market_price ?></td>
        <td><?=$model->shop_price?></td>
        <td><?=$model->stock?></td>
        <td><?=\backend\models\Goods::$saleOpention[$model->is_on_sale]?></td>
        <td><?=\backend\models\Goods::$statusOpention[$model->status]?></td>
        <td><?=date('Y-m-d H:i:s',$model->create_time)?></td>
        <td>
            <?php
            if(Yii::$app->user->can('goods/edit')){
                echo  \yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-xs btn-primary glyphicon glyphicon-edit']);
            }
            if(Yii::$app->user->can('goods/edit')) {
                echo \yii\bootstrap\Html::a('删除', ['goods/del', 'id' => $model->id], ['class' => 'btn btn-xs  btn-danger glyphicon glyphicon-trash']);
            }
            if(Yii::$app->user->can('goods/edit')) {
                echo \yii\bootstrap\Html::a('相册', ['goods/show', 'id' => $model->id], ['class' => 'btn btn-xs  btn-info glyphicon glyphicon-picture']);
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