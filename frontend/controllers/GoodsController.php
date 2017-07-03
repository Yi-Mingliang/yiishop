<?php
namespace frontend\controllers;

use backend\components\SphinxClient;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class GoodsController extends Controller{
 public $layout='list';
    public function actionIndex($id,$brand_id=null){
        $goods_categories=GoodsCategory::find()->where(['=','parent_id',0])->all();
        $goods=GoodsCategory::findOne(['id'=>$id]);
       $brands=Brand::find()->where(['=','status',1])->all();
      if($brand_id){
           $models=Goods::find()->where(['=','brand_id',$brand_id])->all();
       }else{
           $models=Goods::find()->all();
       }

        return $this->render('list',['goods'=>$goods,'models'=>$models,'goods_categories'=>$goods_categories,'brands'=>$brands]);
    }



    public function actionSearch($brand_id=null){
        $query=Goods::find();
        $goods_categories=GoodsCategory::find()->where(['=','parent_id',0])->all();
        //var_dump(\Yii::$app->request->get());exit;
        //sphinxClient  全文搜索
        if($keyword =\Yii::$app->request->get('keywords')){
            $cl = new SphinxClient();//实例化组件 SphinxClient
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');//shopstore_search
            //var_dump($res['matches']);exit;
            if(!isset($res['matches'])){
//                throw new NotFoundHttpException('没有找到xxx商品');
                $query->where(['id'=>0]);
            }else{

                //获取商品id
                //var_dump($res);exit;
                $ids = ArrayHelper::map($res['matches'],'id','id');
                $query->where(['in','id',$ids]);
            }
        }
        $brands=Brand::find()->where(['=','status',1])->all();
        if($brand_id){
            $models=$query->where(['=','brand_id',$brand_id])->all();
        }else{
            $models=$query->all();
        }
        $keywords = array_keys($res['words']);
        $options = array(
            'before_match' => '<span style="color:red;">',
            'after_match' => '</span>',
            'chunk_separator' => '...',
            'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
        );
//关键字高亮
//        var_dump($models);exit;
        foreach ($models as $index => $item) {
            $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
            $models[$index]->name = $name[0];
//            var_dump($name);
        }
        return $this->render('index',['models'=>$models,'goods_categories'=>$goods_categories,'brands'=>$brands]);
    }
}



?>