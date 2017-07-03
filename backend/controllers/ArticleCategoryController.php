<?php

namespace backend\controllers;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Request;

class ArticleCategoryController extends BackendController
{
    public function actionIndex()
    {
        //获取所有的分类 除去状态值为-1的
        $query=ArticleCategory::find()->where(['!=','status','-1']);
        //设置每页显示多少条  总共多少条
        $page=new Pagination([
           'totalCount'=>$query->count(),
            'defaultPageSize'=>2
        ]);
        //限制从什么位置 输出多少条
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    //增加
    public function actionAdd(){
        //实例化数据模型对象
        $model=new ArticleCategory();
        //实例化请求方式对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //保存数据到数据库
                $model->save();
                //返回操作结果到页面
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($id){
        //实例化数据模型对象
        $model=ArticleCategory::findOne($id);
        //实例化请求方式对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //保存数据到数据库
                $model->save();
                //返回操作结果到页面
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article_category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel($id){
        //静态化数据模型对象
        $model=ArticleCategory::findOne($id);
        //逻辑删除
        $model->updateAttributes(['status'=>'-1']);
        //返回操作结果到页面
        \Yii::$app->session->setFlash('success','修改成功');
        return $this->redirect(['article_category/index']);
    }

}
