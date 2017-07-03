<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
            foreach ($models as $model):;
        ?>
        <tr data-goods_id="<?=$model['id']?>">
            <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2_demo.com/'.$model['logo'])?></a>  <strong><?=\yii\helpers\Html::a($model['name'])?></strong></td>
            <td class="col3">￥<span><?=$model['shop_price']?></span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="amount" value="<?=$model['amount']?>" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span><?=$model['shop_price']*$model['amount']?></span></td>
            <td class="col6"><a href="javascript:;" class="del_goods">删除</a></td>
        </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total"></span></strong></td>
        </tr>
        </tfoot>
    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <?=\yii\helpers\Html::a('结 算',['cart/cart2-index'],['class'=>"checkout"])?>
    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this \yii\web\View
 */
$url=\yii\helpers\Url::to(['cart/update-cart']);
$token = Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
      <<<JS
    //监听 数量的增加或减少的点击事件
   $('.reduce_num,.add_num').click(function() {
       //获取到数量
       var amount=$(this).closest('td').find('.amount').val();
       //获取goods_id
      var goods_id= $(this).closest('tr').attr('data-goods_id');
    
       //使用post方式提交 将amount goods_id  提交到cart/update-cart
       $.post("$url",{goods_id:goods_id,amount:amount,"_csrf-frontend":"$token"});   
   })
 $('.del_goods').click(function() {
         console.log(1);
         if(confirm('是否删除该商品')){
               //获取goods_id
      var goods_id= $(this).closest('tr').attr('data-goods_id');
         $.post("$url",{goods_id:goods_id,amount:0,"_csrf-frontend":"$token"});
         $(this).closest('tr').remove();
         }
     })

JS

    )
)



?>
