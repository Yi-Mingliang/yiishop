<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php
                    foreach ($address as $ads):
                    ?>
                    <input type="radio" value="<?=$ads->id?>" name="address_id"/><span class="content_adress"><?=$ads->name?>&emsp;<?=$ads->phone?> &emsp;<?=$ads->province->name?> &emsp;<?=$ads->city->name?>&emsp;<?=$ads->area->name?>&emsp;<?=$ads->detail_address?> </span></p>
                <?php endforeach;?>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($deliveries as $k=>$delivery):
                    ?>
                    <tr class="delivery" data-price="<?=$delivery->delivery_price?>">
                        <td>
                            <input type="radio" name="delivery" <?=($k==0)?"checked":' '?>  value="<?=$delivery->id?>"/><?=$delivery->delivery_name?>
                        </td>
                        <td class="yf$k"><?=$delivery->delivery_price?></td>
                        <td><?=$delivery->intro?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>
            <div class="pay_select">
                <table>
                    <?php
                    foreach ($payments as $payment):
                    ?>
                    <tr >
                        <td class="col1"><input type="radio" name="pay" value="<?=$payment->id?>"/><?=$payment->payment_name?></td>
                        <td class="col2"><?=$payment->intro?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sum=0;
                foreach ($goods as $good):

                ?>
                <tr>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2_demo.com/'.$good['logo'])?></a>  <strong><?=\yii\helpers\Html::a($good['name'])?></strong></td>
                    <td class="col3">￥<?=$good['shop_price']?></td>
                    <td class="col4"><?=$good['amount']?></td>
                    <td class="col5"><?php $sum+=$good['shop_price']*$good['amount']?><span><?=$good['shop_price']*$good['amount']?></span></td>
                </tr>
               <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=count($goods)?>件商品，总商品金额：</span>
                                <em>￥<span id="smalltotal"><?=$sum?></span></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>￥0</em>
                            </li>
                            <li>
                                <span >运费：</span>
                                <em >￥<span id="yf">10.00</span></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em>￥<span id="total1"></span></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a href="javascript:;" id="tj"><span>提交订单</span></a>
        <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
        <p>应付总额：￥<strong id="total2"></strong></p>

    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
 * @var $this yii\web\view
 */
$url=\yii\helpers\Url::to(['cart/add-cart2']);
$token=Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
        <<<JS
//监听
 $('.address_info input').click(function() {
    //获取地址的id值
      address_id=$(this).val();
      })
   $('.pay_select input').click(function() {
    //获取支付方式的id值
      payment_id=$(this).val();
      })
       $('.delivery_select input').click(function() {
    //获取送货方式的id值
      delivery_id=$(this).val();
      var yf=$(this).closest('.delivery').attr('data-price');
      var smalltotal=$('#smalltotal').text();

      $('#yf').text( yf);
      total=(parseFloat(yf)+parseFloat(smalltotal)).toFixed(2);
      
      $('#total1').text(total);
      $('#total2').text(total);
      })
      
      $('#tj').click(function() {
        $.post("$url",{address_id:address_id,payment_id:payment_id,total:total,delivery_id:delivery_id,"_csrf-frontend":"$token"},function(data) {
            // console.log({address_id:address_id,payment_id:payment_id,delivery_id:delivery_id,"_csrf-frontend":"$token"});
          if(data=='success'){
              window.location='http://www.yii2_demo.com/cart/cart3.html';
          }else {
              alert(data);
          }
          
        })
      })
      
JS

))

?>