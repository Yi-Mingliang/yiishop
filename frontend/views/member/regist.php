
<!-- 登录主体部分start -->
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
            <?php
            $form=\yii\widgets\ActiveForm::begin([
                'fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]
            ]);
            echo '<ul>';
            echo $form->field($member,'username')->textInput(['class'=>'txt']);
            echo $form->field($member,'password_hash')->passwordInput(['class'=>'txt']);
            echo $form->field($member,'rePassword')->passwordInput(['class'=>'txt']);
            echo $form->field($member,'email')->textInput(['class'=>'txt']);
            echo $form->field($member,'tel')->textInput(['class'=>'txt']);
            $button =  \yii\helpers\Html::button('发送短信验证码',['id'=>'send_sms_button']);
            echo $form->field($member,'code',['options'=>['class'=>'checkcode'],'template'=>"{label}\n{input}$button\n{hint}\n{error}"])->textInput(['class'=>'txt']);
            echo $form->field($member,'checkCode',['options'=>['class'=>'checkcode']])->widget(yii\captcha\Captcha::className(),['template' =>'{input}{image} ']);
            echo '<li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" value="" class="login_btn" />
                    </li>';
            echo '</ul>';
            \yii\widgets\ActiveForm::end();
            ?>



<!--            <form action="" method="post">-->
<!--                <ul>-->
<!--                    <li>-->
<!--                        <label for="">用户名：</label>-->
<!--                        <input type="text" class="txt" name="username" />-->
<!--                        <p>3-20位字符，可由中文、字母、数字和下划线组成</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">密码：</label>-->
<!--                        <input type="password" class="txt" name="password" />-->
<!--                        <p>6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">确认密码：</label>-->
<!--                        <input type="password" class="txt" name="password" />-->
<!--                        <p> <span>请再次输入密码</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">邮箱：</label>-->
<!--                        <input type="text" class="txt" name="email" />-->
<!--                        <p>邮箱必须合法</p>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">手机号码：</label>-->
<!--                        <input type="text" class="txt" value="" name="tel" id="tel" placeholder=""/>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">验证码：</label>-->
<!--                        <input type="text" class="txt" value="" placeholder="请输入短信验证码" name="captcha" disabled="disabled" id="captcha"/> <input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:3px 8px"/>-->
<!---->
<!--                    </li>-->
<!--                    <li class="checkcode">-->
<!--                        <label for="">验证码：</label>-->
<!--                        <input type="text"  name="checkcode" />-->
<!--                        <img src="images/checkcode1.jpg" alt="" />-->
<!--                        <span>看不清？<a href="">换一张</a></span>-->
<!--                    </li>-->
<!---->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="checkbox" class="chb" checked="checked" /> 我已阅读并同意《用户注册协议》-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <label for="">&nbsp;</label>-->
<!--                        <input type="submit" value="" class="login_btn" />-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </form>-->


        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>
<!-- 登录主体部分end -->
<?php
/* @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['member/send']);
$this->registerJs(new \yii\web\JsExpression(
    <<<JS
    $("#send_sms_button").click(function(){
        //发送验证码按钮被点击时
        var username=$("#member-username").val();
        //手机号
        var tel = $("#member-tel").val();
        //AJAX post提交tel参数到 user/send-sms
        $.post('$url',{tel:tel,username:username},function(data){
            if(data == 'success'){
                console.log('短信发送成功');
                alert('短信发送成功');
            }else{
                console.log(data);
            }
        });
    });
JS
));
