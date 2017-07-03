<?php
use yii\helpers\Html;
?>

<!-- 导航条部分 start -->
<div class="nav w1210 bc mt10">
    <!--  商品分类部分 start-->
    <div class="category fl cat1"> <!-- 非首页，需要添加cat1类 -->
        <div class="cat_hd off">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
            <h2>全部商品分类</h2>
            <em></em>
        </div>

        <div class="cat_bd none">
            <?php
            foreach ($goods_categories as $goods_category){ ?>
                <div class="cat">
                    <h3><?=Html::a("$goods_category->name",['goods/index','id'=>$goods_category->id]);?> <b></b></h3>
                    <div class="cat_detail">
                        <?php
                        if($models=$goods_category->getChildren($goods_category->id)){
                        foreach ($models as $model){
                        ?>

                        <dl class="dl_1st">
                            <dt><?=Html::a("$model->name",[''])?></dt>
                            <?php
                            if($children =$model->getChildren($model->id)){
                                foreach ($children as $child){
                                    ?>
                                    <dd><?=Html::a("$child->name",['goods-detail/detail','goods_category_id'=>$child->id])?></dd>
                                    <?php
                                }
                            }
                            }
                            }
                            ?>
                        </dl>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <!--  商品分类部分 end-->

    <div class="navitems fl">
        <ul class="fl">
            <li class="current"><a href="">首页</a></li>
            <li><a href="">电脑频道</a></li>
            <li><a href="">家用电器</a></li>
            <li><a href="">品牌大全</a></li>
            <li><a href="">团购</a></li>
            <li><a href="">积分商城</a></li>
            <li><a href="">夺宝奇兵</a></li>
        </ul>
        <div class="right_corner fl"></div>
    </div>
</div>
<!-- 导航条部分 end -->
</div>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="order_hd">
            <h3>我的订单</h3>
            <dl>
                <dt>便利提醒：</dt>
                <dd>待付款（0）</dd>
                <dd>待确认收货（0）</dd>
                <dd>待自提（0）</dd>
            </dl>

            <dl>
                <dt>特色服务：</dt>
                <dd><a href="">我的预约</a></dd>
                <dd><a href="">夺宝箱</a></dd>
            </dl>
        </div>

        <div class="order_bd mt10">
            <table class="orders">
                <thead>
                <tr>
                    <th width="10%">订单号</th>
                    <th width="20%">订单商品</th>
                    <th width="10%">收货人</th>
                    <th width="20%">订单金额</th>
                    <th width="20%">下单时间</th>
                    <th width="10%">订单状态</th>
                    <th width="10%">操作</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($orders as $order){
                    //var_dump($order->orderGoods);exit;
                    foreach ($order->orderGoods as $good){;?>
                        <tr>
                    <td><a href=""><?=$order->id?></a></td>
                    <td><a href=""><?=Html::img('http://admin.yii2_demo.com/'.$good->logo)?></a></td>
                    <td><?=$order->name?></td>
                    <td>￥<?=$good->total.' '.$order->payment_name ?></td>
                    <td><?=date('Y-m-d H:i:s',$order->create_time)?></td>
                    <td><?=\frontend\models\Order::$statusOption[$order->status]?></td>
                    <td><a href="">查看</a> | <a href="">删除</a></td>
                </tr>
                <?php
                    }
                }
                ?>


                </tbody>
            </table>
        </div>
    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->