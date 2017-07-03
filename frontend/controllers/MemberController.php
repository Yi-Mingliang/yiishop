<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class MemberController extends \yii\web\Controller
{
    public $layout = 'login';
    //注册
    public function actionRegister(){
        $member=new Member();
        if($member->load(\Yii::$app->request->post()) && $member->validate()){
            if($member->recheck()){
                $member->save(false);
            }

            \Yii::$app->session->setFlash('success','恭喜你注册成功');
           return $this->redirect(['member/login']);
        }
        return $this->render('regist',['member'=>$member]);
    }

    public function actionLogin(){
        $member=new LoginForm();
        if($member->load(\Yii::$app->request->post()) && $member->validate()){
            if($member->check()){
                $cookies=\Yii::$app->request->cookies;
                $cookie=$cookies->get('cart');
                $cart=unserialize($cookie->value);
                $member_id=\Yii::$app->user->identity->id;
                foreach ( $cart as $goods_id=>$amount){
                    $cart=new Cart();
                    //  判断是否具有该用户的该商品的购买记录
                        $cart_goods=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                        if( $cart_goods){//存在
                            $cart_goods->amount+=$amount;
                            $cart_goods->save();
                        }else{
                            $cart->goods_id=$goods_id;
                            $cart->amount=$amount;
                            $cart->member_id=$member_id;
                            $cart->save();
                        }
                    }
                //删除cookie数据
                $cookies=\Yii::$app->response->cookies;
                $cookie=$cookies->get('cart');
                $cookies->remove('cart');
                return $this->redirect(['index/index']);
            }
        }
        return $this->render('login',['member'=>$member]);
    }

public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);
}

    public function actionIndex()
    {
        return $this->render('index');
    }

    //手机验证码
    public function actionSend(){
        //接收传送过来的电话号码
        $tel=\Yii::$app->request->post('tel');
        $name=\Yii::$app->request->post('username');
        if(!preg_match('/^1[345678]\d{9}$/',$tel)){
            echo '不是有效的电话号码';
            exit;
        }
        $code=mt_rand(1000,9999);
        $res=\Yii::$app->sms->setNum($tel)->setParam(['code'=>$code,'name'=>$name])->send();
        if($res){
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
        }else{
            echo '发送失败';
        }
    }



  /*  //手机验证码测试
    public function actionSend(){
// 配置信息
        $config = [
            'app_key'    => '24494623',
            'app_secret' => '489137c2d43d466d668a7fbf64098059',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
// 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $code=rand(1000, 9999);
        $req->setRecNum('15708323016')
            ->setSmsParam([
                'code' =>  $code,
                'name'=>'zhangsan',
            ])
            ->setSmsFreeSignName('亮亮的网站')
            ->setSmsTemplateCode('SMS_71535101');

        $resp = $client->execute($req);
        var_dump($code);
    }*/


}
