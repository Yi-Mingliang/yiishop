<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;


class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {

        //静态化商品分类模型  获取所有的分类
        $models =GoodsCategory::find()->orderBy('tree,lft')->all();

        return $this->render('index',['models'=>$models]);
    }

    //测试嵌套集合插件
//    public function actionTest(){
//        //创建root节点
//        /*$goods_category=new Goods_category(['name'=>'小家电','parent_id'=>0]);
//        $goods_category->makeRoot();
//        var_dump($goods_category);*/
//        //创建非root节点
//        $partend=Goods_category::findOne(['parent_id'=>0]);
//        $goods_category=new Goods_category(['name'=>'电话','parent_id'=>1]);
//        $goods_category->prependTo($partend);
//    }

    public function actionAdd(){
        //实例化商品分类模型  创建对象
        $model=new GoodsCategory();
        //实例化请求方式  创建对象
        $request =new Request();
        //判断是不是post请求
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            if($model->validate()){
                //判断是否是添加一级分类
                if($model->parent_id){//添加非一级分类
                   //找到父节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo( $parent);
                }else{//添加一级分类
                    $model->makeRoot();
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
//        $categories=GoodsCategory::find()->asArray()->all();
        $categories=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }


    //修改
    public function actionEdit($id){
        //实例化商品分类模型  创建对象
        $model=GoodsCategory::findOne($id);
        //实例化请求方式  创建对象
        $request =new Request();
        //判断是不是post请求
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            if($model->validate()){
                //判断是否是添加一级分类
                if($model->parent_id){//添加非一级分类
                    //找到父节点
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    $model->prependTo( $parent);
                }else{
                    //判断是否已经是一级分类
                    if($model->oldAttributes('parent_id')){
                        $model->save();
                    }else{
                        //添加一级分类
                        $model->makeRoot();
                    }
                }
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods-category/index']);
            }
        }
//        $categories=GoodsCategory::find()->asArray()->all();
        $categories=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }


    //删除
    public function actionDel($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        $models=GoodsCategory::findOne(['parent_id'=>$id]);
        if($models){
            \Yii::$app->session->setFlash('danger','该节点下面有子节点，所以不能删除');
            return $this->redirect(['goods-category/index']);
        }else{
            $model->delete();
            \Yii::$app->session->setFlash('success','删除成功');
            return $this->redirect(['goods-category/index']);
        }


    }

}
