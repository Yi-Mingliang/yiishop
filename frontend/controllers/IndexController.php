<?php
namespace frontend\controllers;

use backend\models\GoodsCategory;
use yii\web\Controller;

class IndexController extends Controller{
    public $layout='index';
    public function actionIndex(){
//        $redis=new \Redis();
//        $goods_categories=GoodsCategory::find()->where(['=','parent_id',0])->all();
//        $redis->connect('127.0.0.1');

//        $redis->set('index','$goods_categories');
        return $this->render('index'/*,['goods_categories'=>$goods_categories]*/);
    }


}


?>