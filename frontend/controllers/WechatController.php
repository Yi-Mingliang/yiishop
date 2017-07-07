<?php
namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\Member;
use frontend\models\Order;
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use yii\web\NotFoundHttpException;


class WechatController extends Controller
{
    //微信开发依赖的插件  easyWechat
    //关闭crsf的验证
    public $enableCsrfValidation=false;

    public function actionIndex(){
        $app = new Application(\Yii::$app->params['wechat']);

        $app->server->setMessageHandler(function ($message){
            // $message->FromUserName // 用户的 openid
            // $message->MsgType // 消息类型：event, text....
            //return "您好！欢迎关注我!";
           switch ($message->MsgType){
               case 'text':
                   switch ($message->Content){
                       case '成都':
                           $xml = simplexml_load_file('http://flash.weather.com.cn/wmaps/xml/sichuan.xml');
                           foreach($xml as $city){
                               if($city['cityname'] == '成都'){
                                   $weather = $city['stateDetailed'];
                                   break;
                               }
                           }
                           return '成都的天气情况是：'.$weather;
                           break;
                       case '解除绑定':
                         $openid = \Yii::$app->session->get('openid');
                         return $openid;
                           if($openid){
                               $member=Member::findOne(['openid'=>$openid]);
                               //\Yii::$app->session->remove('openid');
                               if($member){
                                   $member->openid='';
                                   if($member->save()){
                                       return '解除绑定成功';
                                   }
                                   var_dump($member->getErrors());return false;
                               }
                           }
                           return '目前，你尚未绑定账号3333'.$openid;
                           break;
                       case '注册':
                           $url=Url::to(['member/register'],true);
                           return '点击此处注册'.$url;
                           break;
                       case '优惠':
                           $goods=Goods::find()->limit(5)->orderBy("id ASC, name DESC")->all();
                           $nk=[];
                           foreach ($goods as $k=>$good){
                               $k= new News([
                                   'title'       => $good->name.'大减价',
                                   'description' => '图文信息的描述...',
                                   'url'         =>'http://yml.lizhengyu.xin/yii2shop/frontend/web/goods-detail/detail.html?id='.$good->id,
                                   'image'       => 'http://yml.lizhengyu.xin/yii2shop/backend/web'.$good->logo,
                               ]);
                               $nk[]=$k;
                           }
                           return $nk;
                           break;
                       case '帮助':
                           return '您可以发送 优惠、解除绑定 等信息';
                           break;
                   }
                   break;
               case 'event'://事件
                   //事件的类型   $message->Event
                   //事件的key值  $message->EventKey
                   if($message->Event == 'CLICK'){//菜单点击事件
                       if($message->EventKey == 'latest activeity'){
                           $goods=Goods::find()->limit(5)->orderBy("id ASC, name DESC")->all();
                           $nk=[];
                           foreach ($goods as $k=>$good){
                               $k= new News([
                                   'title'       => $good->name.'大减价',
                                   'description' => '图文信息的描述...',
                                   'url'         =>'http://yml.lizhengyu.xin/yii2shop/frontend/web/goods-detail/detail.html?id='.$good->id,
                                   'image'       => 'http://yml.lizhengyu.xin/yii2shop/backend/web'.$good->logo,
                               ]);
                               $nk[]=$k;
                           }
                           return $nk;
                       }
                   }

                   return '接受到了'.$message->Event.'类型事件'.'key:'.$message->EventKey;
                   break;
           }
        });

        $response = $app->server->serve();
        // 将响应输出
        $response->send(); // Laravel 里请使用：return $response;
    }

    //设置菜单  一级菜单最多设置3个  二级菜单最多设置5个
    public function actionSetMenu(){
        $app = new Application(\Yii::$app->params['wechat']);
        $menu = $app->menu;
        $buttons = [
            [
                "type" => "view",
                "name" => "在线商城",
                "url"  => Url::to(['index/index'],true)
            ],
            [
                "type" => "click",
                "name" => "促销商品",
                "key"  => "latest activeity"
            ],
            [
                "name"       => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "收货地址",
                        "url"  => Url::to(['wechat/address'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => Url::to(['wechat/order'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "绑定账户",
                        "url" => Url::to(['wechat/login'],true)
                    ],
                    [
                        "type" => "view",
                        "name" => "修改密码",
                        "url" => Url::to(['wechat/edit-password'],true)
                    ],
                ],
            ],
        ];
        $menu->add($buttons);
        //获取已设置的菜单（查询菜单）
        $menus = $menu->all();
        var_dump($menus);
    }

    //个人中心
    public function actionUser()
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        var_dump($openid);
    }

    //获取已授权的用户
    public function actionCallback(){
        $app=new Application(\Yii::$app->params['wechat']);
        $user = $app->oauth->user();
// $user 可以用的方法:
// $user->getId();  // 对应微信的 OPENID
// $user->getNickname(); // 对应微信的 nickname
// $user->getName(); // 对应微信的 nickname
// $user->getAvatar(); // 头像网址
// $user->getOriginal(); // 原始API返回的结果
// $user->getToken(); // access_token， 比如用于地址共享时使用
        //将已授权的用户信息（openid）保存到session中

        \Yii::$app->session->set('openid',$user->getId());
      $openid = \Yii::$app->session->get('openid','empty');


        //返回授权页
        return $this->redirect([\Yii::$app->session->get('redirect')]);
    }

    //我的订单
    public function actionOrder()
    {
        //openid
        $openid = \Yii::$app->session->get('openid');
//        if($openid == null){
//            //获取用户的基本信息（openid），需要通过微信网页授权
//            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
//            //echo 'wechat-user';
//            $app = new Application(\Yii::$app->params['wechat']);
//            //发起网页授权
//            $response = $app->oauth->scopes(['snsapi_base'])
//                ->redirect();
//            $response->send();
//        }
        //var_dump($openid);
        //通过openid获取账号
        $member = Member::findOne(['openid'=>$openid]);
        if($member == null){
            //该openid没有绑定任何账户
            //引导用户绑定账户
            return $this->redirect(['wechat/login']);
        }else{
            //已绑定账户
            $orders = Order::findAll(['member_id'=>$member->id]);
           return $this->render('order',['orders'=>$orders]);
        }
    }

    //绑定用户账号   将openid和用户账号绑定
    public function actionLogin()
    {
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }

        //让用户登录，如果登录成功，将openid写入当前登录账户
        $request = \Yii::$app->request;
        if(\Yii::$app->request->isPost){
            $user = Member::findOne(['username'=>$request->post('username')]);
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                \Yii::$app->user->login($user);
                //如果登录成功，将openid写入当前登录账户
                Member::updateAll(['openid'=>$openid],'id='.$user->id);
                if(\Yii::$app->session->get('redirect')) {
                    return $this->redirect([\Yii::$app->session->get('redirect')]);
                    echo '绑定成功';exit;
                }
            }else{
                echo '登录失败';exit;
            }
        }

        return $this->renderPartial('login');
    }


    //我的收货地址
    public function actionAddress(){
        $openid = \Yii::$app->session->get('openid');
        if($openid == null){
            //获取用户的基本信息（openid），需要通过微信网页授权
            \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
            //echo 'wechat-user';
            $app = new Application(\Yii::$app->params['wechat']);
            //发起网页授权
            $response = $app->oauth->scopes(['snsapi_base'])
                ->redirect();
            $response->send();
        }
        //通过openid获取账号
        $member = Member::findOne(['openid'=>$openid]);
        if($member == null){
            //该openid没有绑定任何账户
            //引导用户绑定账户
            return $this->redirect(['wechat/login']);
        }else{
            //已绑定账户
            $address = Address::findAll(['member_id'=>$member->id]);
            return $this->renderPartial('list',['address'=>$address]);
        }

    }

    //修改密码
    public function actionEditPassword(){
        //获取openid
        $openid = \Yii::$app->session->get('openid');
        //var_dump($openid);exit;
        if(\Yii::$app->request->isPost==false){
            if($openid == null){
                //设置用户的当前路由
                \Yii::$app->session->set('redirect',\Yii::$app->controller->action->uniqueId);
                $app = new Application(\Yii::$app->params['wechat']);
                //发起网页授权
                $response = $app->oauth->scopes(['snsapi_base'])
                    ->redirect();
                $response->send();
            }

            $member=Member::findOne(['openid'=>$openid]);
            if($member == null){
                return $this->redirect(['wechat/login']);
            }else{
                return $this->renderPartial('edit');
            }
        }else{
            $member=Member::findOne(['openid'=>$openid]);
            $oldpassword=\Yii::$app->request->post('oldpassword');
            $newpassword=\Yii::$app->request->post('newpassword');
            $repassword=\Yii::$app->request->post('repassword');
            if($newpassword != $repassword){
                throw new NotFoundHttpException('两次输入的密码不一致！');
            }
            if(\Yii::$app->security->validatePassword($oldpassword,$member->password_hash)){
                $member->password_hash=\Yii::$app->security->generatePasswordHash($newpassword);
                $member->save();
                throw new NotFoundHttpException('密码修改成功');
            }else{
                throw new NotFoundHttpException('原密码不正确');
            }
        }

    }


//    //解除绑定
//public function actionDel(){
//    $openid = \Yii::$app->session->get('openid');
//    if($openid){
//        $member=Member::findOne(['openid'=>$openid]);
//        \Yii::$app->session->remove('openid');
//        if($member){
//            $member->openid='';
//            if($member->save()){
//                return '解除绑定成功';
//            }
//            var_dump($member->getErrors());return false;
//        }
//    }
//    return '目前，你尚未绑定账号';
//}


    /**
     * 开发需求
     * 1.菜单设置
     * -促销商品（click）
     * -在线商城（view：跳转至商城首页）
     * -个人中心
     * ---绑定账户（view）
     * ---我的订单（view）
     * ---收货地址（view）
     * ---修改密码（view）
     * 2.详细功能
     *  点击【促销商品】，发送图文信息（发送5条任意商品信息），点击图文信息中的商品，跳转至商品详情页
     *  点击【在线商城】，跳转至商城首页
     * 点击 【我的订单】，【收货地址】，【修改密码】，判断用户是否绑定账户，如果没有绑定则跳转至绑定账户页面
     * 3.对话功能
     * 用户发送【帮助】，回复以下信息“您可以发送 优惠、解除绑定 等信息”
     * 用户发送【优惠】，效果和点击【促销商品】相同
     * 用户发送【解除绑定】，如用户已绑定账户，则解绑当前openid，并回复解绑成功；否则回复请先绑定账户及绑定页面地址
     */


    public function actionD(){
        \Yii::$app->session->removeAll();
    }





}


?>