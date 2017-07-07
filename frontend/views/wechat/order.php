<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>WeUI</title>
    <!-- 引入 WeUI -->
    <link rel="stylesheet" href="//res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>
</head>
<body>

<div class="page">
    <div class="page__hd">
        <h1 class="page__title">收货地址列表</h1>
        <p class="page__desc">收货列表</p>
    </div>

    <div class="page__bd">
        <div class="weui-cells__title">带说明的列表项</div>

        <div class="weui-cells">
            <?php foreach ($orders as $order):?>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>ID</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->id?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>收货人</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->name?></div>
                </div>

                <!--                <div class="weui-cell__ft">-->
                <!--                    <a class="weui-swiped-btn weui-swiped-btn_warn" href="javascript:">删除</a>-->
                <!--                </div>-->
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>详细地址</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->address?></div>
                </div>

                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>手机号码</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->tel?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>邮寄方式</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->delivery_name?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>运费</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->delivery_price?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>支付方式</p>
                    </div>
                    <div class="weui-cell__ft"><?PHP echo $order->payment_name;?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>订单总金额</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->total?></div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <p>第三方交易单号</p>
                    </div>
                    <div class="weui-cell__ft"><?=$order->trade_no?></div>
                </div>
            <?php endforeach;?>
        </div>

        <!--        <div class="weui-cells__title">带图标、说明的列表项</div>-->
        <!--        <div class="weui-cells">-->
        <!--            <div class="weui-cell">-->
        <!--                <div class="weui-cell__hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>标题文字</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">说明文字</div>-->
        <!--            </div>-->
        <!--            <div class="weui-cell">-->
        <!--                <div class="weui-cell__hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>标题文字</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">说明文字</div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!---->
        <!--        <div class="weui-cells__title">带跳转的列表项</div>-->
        <!--        <div class="weui-cells">-->
        <!--            <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>cell standard</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">-->
        <!--                </div>-->
        <!--            </a>-->
        <!--            <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>cell standard</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">-->
        <!--                </div>-->
        <!--            </a>-->
        <!--        </div>-->
        <!---->
        <!--        <div class="weui-cells__title">带说明、跳转的列表项</div>-->
        <!--        <div class="weui-cells">-->
        <!--            <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>cell standard</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">说明文字</div>-->
        <!--            </a>-->
        <!--            <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>cell standard</p>-->
        <!--                </div>-->
        <!--                <div class="weui-cell__ft">说明文字</div>-->
        <!--            </a>-->
        <!---->
        <!--        </div>-->
        <!---->
        <!--        <div class="weui-cells__title">带图标、说明、跳转的列表项</div>-->
        <!--        <div class="weui-cells">-->
        <!---->
        <!--            <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--                <div class="weui-cell__hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>-->
        <!--                <div class="weui-cell__bd">-->
        <!--                    <p>cell standard</p>-->
        <!--                </div>-->
        <!--    <div class="weui-cell__ft">说明文字</div>-->
        <!--    </a>-->
        <!--    <a class="weui-cell weui-cell_access" href="javascript:;">-->
        <!--        <div class="weui-cell__hd"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAuCAMAAABgZ9sFAAAAVFBMVEXx8fHMzMzr6+vn5+fv7+/t7e3d3d2+vr7W1tbHx8eysrKdnZ3p6enk5OTR0dG7u7u3t7ejo6PY2Njh4eHf39/T09PExMSvr6+goKCqqqqnp6e4uLgcLY/OAAAAnklEQVRIx+3RSRLDIAxE0QYhAbGZPNu5/z0zrXHiqiz5W72FqhqtVuuXAl3iOV7iPV/iSsAqZa9BS7YOmMXnNNX4TWGxRMn3R6SxRNgy0bzXOW8EBO8SAClsPdB3psqlvG+Lw7ONXg/pTld52BjgSSkA3PV2OOemjIDcZQWgVvONw60q7sIpR38EnHPSMDQ4MjDjLPozhAkGrVbr/z0ANjAF4AcbXmYAAAAASUVORK5CYII=" alt="" style="width:20px;margin-right:5px;display:block"></div>-->
        <!--        <div class="weui-cell__bd">-->
        <!--            <p>cell standard</p>-->
        <!--        </div>-->
        <!--        <div class="weui-cell__ft">说明文字</div>-->
        <!--    </a>-->
        <!--</div>-->
    </div>
    <div class="page__ft">
        <a href="javascript:home()"><img src="./images/icon_footer_link.png" /></a>
    </div>
</div>




</body>