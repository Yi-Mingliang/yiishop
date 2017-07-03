<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends BackendController
{
    //显示列表
    public function actionIndex()
    {
        //获取所有的分类  除了状态值为-1的
        $query=Article::find()->where(['!=','status','-1']);
        //每页显示多少条  总条数
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2,
        ]);
        //从什么位置  输出几条
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //增加
    public function actionAdd(){
        //实例化数据模型对象
        $model=new Article();
        //实例化数据模型对象
        $detail=new ArticleDetail();
        //静态化数据模型对象
        $article_category=ArticleCategory::find()->all();
        //实例化请求方式对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证数据
            if($model->validate() && $detail->validate()){
                //保存数据到数据库
                $model->create_time=time();
                $model->save();
                $detail->article_id=$model->id;
                $detail->save();
                //返回操作结果到页面
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'article_category'=>$article_category,'detail'=>$detail]);
    }
    //修改
    public function actionEdit($id){
        //实例化数据模型对象
        $model=Article::findOne($id);
        //实例化数据模型对象
        $detail=ArticleDetail::findOne($id);
        //静态化数据模型对象
        $article_category=ArticleCategory::find()->all();
        //实例化请求方式对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $detail->load($request->post());
            //验证数据
            if($model->validate()){
                //保存数据到数据库
                $model->save();
//                $detail->article_id=$model->id;
                $detail->save();
                //返回操作结果到页面
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['article/index']);
            }
        }
        return $this->render('add',['model'=>$model,'article_category'=>$article_category,'detail'=>$detail]);
    }

    //删除
    public function actionDel($id){
        //实例化数据模型对象
        $model=Article::findOne($id);
        //设置状态值为-1
        $model->updateAttributes(['status'=>'-1']);
        //显示操作结果
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }

    //文章详情
    public function actionDetail($id){
        //实例化数据模型对象
        $detail=ArticleDetail::findOne($id);
        $article=Article::findOne($id);
        //视图显示
        return $this->render('detail',['detail'=>$detail,'article'=>$article]);
    }

    //ueditor百度编辑器
    public function actions()
    {
        return [
            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]
            ],
        ];
    }
}
