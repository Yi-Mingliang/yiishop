<?php
namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class CartController extends Controller{
    public $layout='cart';

    public function actionAdd(){
        //先接受post传的goods_id   amount
        $goods_id=\Yii::$app->request->post('goods_id');
//        var_dump($goods_id);exit;
        $amount=\Yii::$app->request->post('amount');
        $good=Goods::findOne(['id'=>$goods_id]);
        //判断该商品是否存在
        if($good==null){
            throw new NotFoundHttpException('该商品不存在');
        }
        //判断是否已经登录
        if(\Yii::$app->user->isGuest){
            //未登录   将数据保存到cookie中
            //先获取购物车的cookie
            $cookies=\Yii::$app->request->cookies;
            //判断cookie中是否存在商品
            $cookie=$cookies->get('cart');
            if($cookie){
              $cart=unserialize($cookie->value);
            }else{
                $cart=[];
            }

            //判断cookie中是否存在该商品
            if(key_exists($good->id,$cart)){
                $cart[$goods_id] +=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }

            //将goods_id 和商品数量添加到cookie中
            $cookies=\Yii::$app->response->cookies;
            //实例化cookie
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart),
            ]);
            $cookies->add($cookie);
        }else{
            $cart=new Cart();
            $member_id=\Yii::$app->user->identity->id;
            //  判断是否具有该用户的该商品的购买记录
            $cart_goods=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
            if( $cart_goods){//存在
                $cart_goods->amount+=$amount;
                if($cart_goods->validate()){
                    $cart_goods->save();
                }
            }else{
                $cart->goods_id=$goods_id;
                $cart->amount=$amount;
                $cart->member_id=$member_id;
                if($cart->validate()){
                    $cart->save();
                }
            }
    }
        return $this->redirect(['cart/cart']);
    }

    public function actionCart(){
        //判断用户是否登录
        if(\Yii::$app->user->isGuest){
            //未登录
            //从cookie中取出goods_id 和商品数量
            $cookies=\Yii::$app->request->cookies;
            $cookie=$cookies->get('cart');
            if($cookie){
                $cart=unserialize($cookie->value);
            }else{
                $cart=[];
            }
            $models=[];
            foreach ($cart as $goods_id=>$amount){
                $good=Goods::findOne(['id'=>$goods_id])->attributes;
                $good['amount']=$amount;
                $models[] =$good;
            }

        }else{
            $member_id=\Yii::$app->user->identity->id;
            $cart=Cart::find()->where(['member_id'=>$member_id])->all();
            $models=[];
            foreach ($cart as $amount){
                $good=Goods::findOne(['id'=>$amount->goods_id])->attributes;
                $good['amount']=$amount->amount;
                $models[] =$good;
            }

        }

        return $this->render('cart',['models'=>$models]);
    }

    public function actionUpdateCart(){
        //先接受post传的goods_id   amount
        $goods_id=\Yii::$app->request->post('goods_id');
//        var_dump(\Yii::$app->request->post());exit;
        $amount=\Yii::$app->request->post('amount');
        $good=Goods::findOne(['id'=>$goods_id]);
        //判断该商品是否存在
        if($good==null){
            throw new NotFoundHttpException('该商品不存在');
        }
        //判断是否已经登录
        if(\Yii::$app->user->isGuest){
            //未登录   将数据保存到cookie中
            //先获取购物车的cookie
            $cookies=\Yii::$app->request->cookies;
            //判断cookie中是否存在商品
            $cookie=$cookies->get('cart');
            if($cookie){
                $cart=unserialize($cookie->value);
            }else{
                $cart=[];
            }
            if($amount){
                //判断cookie中是否存在该商品
                if(key_exists($good->id,$cart)){
                    $cart[$goods_id]=$amount;
                }else{
                    $cart[$goods_id]=$amount;
                }
            }else{
                unset( $cart[$goods_id]);
            }

            //将goods_id 和商品数量添加到cookie中
            $cookies=\Yii::$app->response->cookies;
            //实例化cookie
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart),
            ]);
            $cookies->add($cookie);
        }else{
            $cart=new Cart();
            //已登录
            $member_id=\Yii::$app->user->identity->id;
                //存在  判断是否具有该商品的购买记录
                $cart_goods=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                if( $cart_goods){//存在
                    if($amount){
                        $cart_goods->amount=$amount;
                        if($cart_goods->validate()){
                            $cart_goods->save();
                        }
                    }else{
                        $cart_goods->delete();
                    }

                }else{
                    $cart->goods_id=$goods_id;
                    $cart->amount=$amount;
                    $cart->member_id=$member_id;
                    if($cart->validate()){
                        $cart->save();
                    }
                }
            }
        }
        //购物车第二步
        public function actionCart2Index(){
            $this->layout='cart2';
            if(!$member_id=\Yii::$app->user->getId()){
              throw new NotFoundHttpException('只有登录后，才能填写订单，请登录');
            }else{
            $deliveries=Delivery::find()->all();
            $payments=Payment::find()->all();
            $address=Address::find()->where(['member_id'=>$member_id])->all();
            $cart=Cart::find()->where(['member_id'=>$member_id])->all();

            $goods=[];
            foreach ($cart as $amount){
                    $good=Goods::findOne(['id'=>$amount->goods_id])->attributes;
                    $good['amount']=$amount->amount;
                    $goods[] =$good;
            }
            return $this->render('cart2',['payments'=>$payments,'deliveries'=>$deliveries,'address'=>$address,'goods'=>$goods]);
            }
        }


        public function actionAddCart2(){

            $address_id=\Yii::$app->request->post('address_id');
            $payment_id=\Yii::$app->request->post('payment_id');
            $delivery_id=\Yii::$app->request->post('delivery_id');
            $member_id=\Yii::$app->user->getId();
            $address=Address::findOne(['id'=>$address_id,'member_id'=>$member_id]);
            $payment=Payment::findOne(['id'=>$payment_id]);
            $delivery=Delivery::findOne(['id'=>$delivery_id]);
            $model=new Order();
            $model->member_id=$member_id;
            $model->name=$address->name;
            $model->province=$address->province->name;
            $model->city=$address->city->name;
            $model->area=$address->area->name;
            $model->address=$model->province.$model->city.$model->area.$address->detail_address;
            $model->tel=$address->phone;
            $model->delivery_id=$delivery->id;
            $model->delivery_name=$delivery->delivery_name;
            $model->delivery_price=$delivery->delivery_price+0.00;
            $model->payment_id=$payment->id;
            $model->payment_name=$payment->payment_name;
            $model->total=\Yii::$app->request->post('total')+0.00;
//            status	int	订单状态（0已取消1待付款2待发货3待收货4完成）
//trade_no	varchar	第三方支付交易号
//create_time	int	创建时间
            $model->status=1;
            $model->create_time=time();
            $model->trade_no='0';
            //开启事务
            $transaction=\Yii::$app->db->beginTransaction();
           $model->save();

           try{
               $carts=Cart::findAll(['member_id'=>$member_id]);
               foreach ($carts as $cart) {
                   $order_goods = new OrderGoods();
                   $good = Goods::findOne(['id' => $cart->goods_id,'status'=>1]);
                   if($good==null){
                       throw new Exception('该商品已售完');

                   }
                   if($good->stock<$cart->amount){
                       throw  new Exception('该商品的库存不足');
                   }
                   $order_goods->order_id = $model->id;
                   $order_goods->goods_id = $good->id;
                   $order_goods->goods_name = $good->name;
                   $order_goods->logo = $good->logo;
                   $order_goods->price = $good->shop_price;
                   $order_goods->amount = $cart->amount;
                   $order_goods->total = ($cart->amount) * ($good->shop_price);
                   $order_goods->save();
                   //扣除商品的库存
                   $good->stock -=$cart->amount;
                   $good->save();

                }
                Cart::deleteAll(['member_id'=>$member_id]);
               //提交事务
               $transaction->commit();
                echo  'success';
           }catch (Exception $e){
               //事务回滚
               $transaction->rollBack();
                echo $e->getMessage();
           }
        }

        //自动清除订单
    public function actionClean(){
        //设置执行时间限制
        set_time_limit(0);
        while (1){
            //订单在一个小时后，还没有支付，就自动将订单取消
            $orders=Order::find()->Where(['<','create_time',time()-3600])->andWhere(['=','status',1])->all();
            foreach ($orders as $order){
                $order->updateAttributes(['status'=>1]);
                foreach ($order->orderGoods as $model){
                    //回滚商品的库存数量
                    $good=Goods::findOne(['id'=>$model->goods_id]);
                    $good->updateAttributes(['stock'=>($good->stock+$model->amount)]);
                }
            }
            //1秒钟执行一次
            sleep(1);
        }
    }




        //订单详情页
    public function actionOrder(){
        $this->layout='order-list';
        if(!$member_id=\Yii::$app->user->getId()){
            throw new NotFoundHttpException('只有登录后，才能填写订单，请登录');
        }else {
            $goods_categories = GoodsCategory::find()->where(['=', 'parent_id', 0])->all();
            $orders=Order::findAll(['member_id'=>$member_id]);
            return $this->render('order',['goods_categories'=>$goods_categories,'orders'=>$orders]);
        }
    }






        public function actionCart3(){
            $this->layout='cart3';
            return $this->render('cart3');
        }


        public function actionOrderGoods(){
            $carts=Cart::findAll(['member_id'=>\Yii::$app->user->getId()]);

            foreach ($carts as $cart){
                $order_goods=new OrderGoods();
                $good=Goods::findOne(['id'=>$cart->goods_id]);
//                $order_goods->order_id= $model->id;
                $order_goods->goods_id=$good->id;
                $order_goods->goods_name=$good->name;
                $order_goods->logo=$good->logo;
                $order_goods->price=$good->shop_price;
                $order_goods->amount=$cart->amount;
                $order_goods->total=($cart->amount)*($good->shop_price);
               //var_dump($order_goods);exit;
                $order_goods->save();

            }
        }

        public function actionK(){
            $k='123456';
            var_dump($k);
            var_dump((float)$k);
        }

        public function actionA(){
            $cookies=\Yii::$app->response->cookies;
            $cart[1]=[1];
            $cookie=new Cookie([
                'name'=>'cart','value'=>serialize($cart),
            ]);
            $cookies->add($cookie);
            var_dump($cookies->get('cart'));
        }

        public function actionB(){
            $cookies=\Yii::$app->response->cookies;
            var_dump($cookies->get('cart'));
//            $cookies->remove($cookies->get('cart'));
//            $cookies->remove('cart');

        }
        public function actionD(){
            $cookies=\Yii::$app->request->cookies;
            var_dump($cookies->get('cart'));
        }
}


?>