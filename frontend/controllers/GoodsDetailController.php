<?php
namespace frontend\controllers;

use backend\models\GoodsAlbum;
use yii\web\Controller;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;


class GoodsDetailController extends Controller{

    public $layout='detail';
    public function actionDetail($id=0,$goods_category_id=0){

        $goods_categories=GoodsCategory::find()->where(['=','parent_id',0])->all();
        $good=Goods::findOne(['id'=>$id]);
        $goods_albums=GoodsAlbum::findAll(['goods_id'=>$id]);
        return $this->render('detail',['goods_categories'=>$goods_categories,'good'=>$good,'goods_albums'=>$goods_albums]);
    }





}


?>