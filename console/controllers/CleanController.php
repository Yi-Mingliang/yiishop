<?php
namespace console\controllers;


use backend\models\Goods;
use frontend\models\Order;
use yii\console\Controller;

class CleanController extends Controller{
    //自动清除订单
    public function actionA(){
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
}


?>