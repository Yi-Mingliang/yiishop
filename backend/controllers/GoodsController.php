<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsAlbum;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsSearch;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //实例化商品搜索模型
        $goodsSearch=new GoodsSearchForm();
        //静态化商品模型 获取所有的分类
        $query=Goods::find()->andWhere(['=','status','1']);
        //定义每一页显示多少条  总共多条数据
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2,
        ]);
        //接收表单提交过来的查询参数
        $goodsSearch->search($query);
        //限制输出  从什么位置输出  输出多少条
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page,'goodsSearch'=>$goodsSearch]);
    }
    //添加商品
    public function actionAdd(){
        $goodsIntro=new GoodsIntro();
        //实例化商品模型  创建对象
        $model=new Goods();
        //实例化请求方式 创建对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $goodsIntro->load($request->post());
            //后台验证数据
            if($model->validate() && $goodsIntro->validate()){
                $newTime=date('Y-m-d',time());
                //先判断当天goods_day_count表中的活动记录是否为空
                if($count=GoodsDayCount::findOne(['day'=>$newTime])){
                    $count->count+=1;
                }else{
                    $count=new GoodsDayCount();
                    $count->count=1;
                }
                $count->day=$newTime;
                $count->save();
                $model->create_time=time();
                $model->sn=date('Ymd',time()).str_pad($count->count,5,0,STR_PAD_LEFT);
                $model->save();
                $goodsIntro->goods_id=$model->id;
                $goodsIntro->save();
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['goods/index']);
            }
        }

        $brand=Brand::find()->all();
       $categories=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'brand'=>$brand,'categories'=>$categories,'goodsIntro'=>$goodsIntro]);
    }
    public function actionEdit($id){
        $goodsIntro=GoodsIntro::findOne(['goods_id'=>$id]);
        //实例化商品模型  创建对象
        $model=Goods::findOne($id);
        //实例化请求方式 创建对象
        $request=new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            $goodsIntro->load($request->post());
            //后台验证数据
            if($model->validate() && $goodsIntro->validate()){
                $newTime=date('Y-m-d',time());
                //先判断当天goods_day_count表中的活动记录是否为空
                if($count=GoodsDayCount::findOne(['day'=>$newTime])){
                    $count->count+=1;
                }else{
                    $count=new GoodsDayCount();
                    $count->count=1;
                }
                $count->day=$newTime;
                $count->save();
                $model->create_time=time();
                $model->sn=date('Ymd',time()).str_pad($count->count,5,0,STR_PAD_LEFT);
                $model->save();
                $goodsIntro->goods_id=$model->id;
                $goodsIntro->save();
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['goods/index']);
            }
        }

        $brand=Brand::find()->all();
        $categories=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'brand'=>$brand,'categories'=>$categories,'goodsIntro'=>$goodsIntro]);
    }


    //删除 逻辑上的
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        $model->status=0;
            $model->save();
    }

    //商品相册
    public function actionShow($id){
        $goods=Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('album',['goods'=>$goods]);
    }

    //ajax相册删除
    public function actionDelAlbum(){
        //接收参数id
        $id=\Yii::$app->request->post('id');
        $model=GoodsAlbum::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }
    }

//文件上传插件
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $action->output['fileUrl'] = $action->getWebUrl();
                }
            ],
            'ueditor' => [//ueditor百度编辑器
                     'class' => 'crazyfd\ueditor\Upload',
                     'config'=>[
                         'uploadDir'=>date('Y/m/d')
                     ]
                 ],
            'album-upload'=>[
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/logo',
                'baseUrl' => '@web/upload/logo',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "/{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片上传成功的同时，将图片和商品关联起来
                    $model = new GoodsAlbum();
                    $model->goods_id = \Yii::$app->request->post('goods_id');
                    $model->path = $action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->path;
                }
            ]
        ];
    }




}
