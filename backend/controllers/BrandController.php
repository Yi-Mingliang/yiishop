<?php

namespace backend\controllers;



use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;
class BrandController extends BackendController
{
    //显示列表
    public function actionIndex()
    {
        //获取所有的分类
        $query=Brand::find()->where(['!=','status','-1']);
        //总条数
        $total=$query->count();
        $page=new Pagination([
                'totalCount'=>$total,
                'defaultPageSize'=>2,//每页显示多少条
            ]);
        //限制输出 offset 从第几条开始   limit 输出几条
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }

    //增加品牌
    public function actionAdd(){
        //实例化数据模型对象
//        $model=new Brand(['scenario'=>Brand::SCENARIO_ADD]);
        $model=new Brand();
        //实力化请求方式对象
        $request= new Request();
        //判断请求方式是不是post
        if($request->isPost){
            //接收数据
            $model->load($request->post());
            //接收图片的数据
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //后台数据验证
            if($model->validate()){
//                //保存图片路径
//                $fileName='/imgages/'.date('Ymd').mt_rand(100,10000).'.'.$model->imgFile->extension;
//                //将图片保存到硬盘上
//                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                $model->logo=$fileName;
                $model->save();
                //自动跳转到列表
                \Yii::$app->session->setFlash('success','增加成功');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        //静态化数据模型对象
        $model=Brand::findOne($id);
        //指定背景
//        $model->scenario=Brand::SCENARIO_EDIT;
        //实力化请求方式对象
        $request= new Request();
        //判断请求方式是不是post
        if($request->isPost) {
            //接收数据
            $model->load($request->post());
            //接收图片的数据
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //后台验证数据
            if($model->validate()){
//                if($model->imgFile){
//                    //保存图片的路径
//                    $fileName='/imgages/'.date('Ymd').mt_rand(100,10000).'.'.$model->imgFile->extension;
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
//                    $model->logo=$fileName;
//                }
                //保存数据
                $model->save();
                \Yii::$app->session->setFlash('success','修改成功！');
                return $this->redirect(['brand/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model=Brand::findOne($id);
//        $model->status= -1;
//        $model->save();
        $model->updateAttributes(['status'=>'-1']);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['brand/index']);
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
                    $imgUrl=$action->getSavePath();
//                    $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云的组件 将图片上传到七牛云
                    $qiniu=\Yii::$app->qiniu;
                    $qiniu->uploadFile($imgUrl,$action->getWebUrl());
                    //获取该图片在七牛云的位置
                    $url=$qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl']=$url;
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                },
            ],


        ];


    }
//    //将文件上传至七牛云
//    public function actionUpfile(){
////        $ak = 'w8yJxLDVymdtbNjQyDV80XcmKAFMJyuw2fOn1R7n';
////        $sk = 'nRjYm5KaxuhuX4K5RRY2dtDPaoHaNs4bl39fJuvi';
////        $domain = 'http://or9qbn08z.bkt.clouddn.com/';
////        $bucket = 'php0217';
//
//        //$qiniu = new Qiniu($ak, $sk,$domain, $bucket);
//        //要上传的文件路径
//        $filename=\Yii::getAlias('@webroot').'/upload/1.png';
////        var_dump($filename);exit;
//        $key = '1.png';
//        $qiniu->uploadFile($filename,$key);
//        $url = $qiniu->getLink($key);
//        var_dump($url);
//    }


}
