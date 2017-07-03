<?php
namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Address;
use frontend\models\LoginForm;
use frontend\models\Member;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends Controller{
    //关闭跨站防御
    public $enableCsrfValidation=false;

    public function init()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        parent::init();
    }

    public function actionGetGoods(){
        $brand_id=\Yii::$app->request->get('brand_id');
        /*\Yii::$app->response->format=Response::FORMAT_JSON;*/
        if($brand_id&& $goods=Goods::findAll(['brand_id'=>$brand_id])){
            return ['status'=>1,'errormsg'=>null,'data'=>$goods];
        }
        return ['status'=>1,'errormsg'=>'参数有错','data'=>null];
    }

//会员注册
    public function actionRegister(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $member=new Member();
            $member->username=$request->post('username');
            $member->password_hash=$request->post('password_hash');
            $member->rePassword=$request->post('rePassword');
            $member->email=$request->post('email');
            $member->tel=$request->post('tel');
            if($member->validate()){
                $member->save();
                return ['status'=>1,'errormsg'=>null,'data'=>$member->toArray()];
            }
            return ['status'=>1,'errormsg'=>$member->getErrors()];
        }
        return ['status'=>1,'errormsg'=>'请用post方式提交'];
    }


    //登录
    public function actionLogin(){
        $request=new Request();
        if($request->isPost){
        $member=new LoginForm();
        $member->username=$request->post('username');
        $member->password_hash=$request->post('password_hash');
        $member->rememberMe=$request->post('rememberMe');


        if($member->validate()){
            if($member->check()){
                return ['status'=>1,'errormsg'=>null,'data'=>['username'=>$member->username]];
            }
        }
            return ['status'=>1,'errormsg'=>$member->getErrors()];
        }
        return ['status'=>1,'errormsg'=>'请使用post方式提交'];
    }


    //修改密码
    public function actionUpdatePassword(){
        if($member_id=\Yii::$app->user->getId()){
            $request=new Request();
            if($request->isPost){
                $member=Member::findOne(['id'=>$member_id]);
                $old_password=$request->post('old_password');
                if(\Yii::$app->security->validatePassword($old_password,$member->password_hash)){
                    $new_password=$request->post('new_password');
                    $member->password_hash=\Yii::$app->security->generatePasswordHash($new_password);
                    $member->save();
                    return ['status'=>true,'error_msg'=>null,'data'=>null];
                }
              return ['status'=>false,'error_msg'=>$member->getErrors(),'data'=>null];
            }
            return ['status'=>false,'error_msg'=>'请用post方式提交','data'=>null];
        }else{
            return ['status'=>false,'error_msg'=>'目前尚未登录，请登录','data'=>null];
        }
    }

    //添加收货地址
    public function actionAddress(){
        if($member_id=\Yii::$app->user->getId()){
            $request=new Request();
            if($request->isPost){
                $address=new Address();
                $address->load($request->post(),'');
                if($address->validate()){
                    $address->member_id=$member_id;
                    $address->save();
                    return ['status'=>ture,'error_msg'=>null];
                }
                return ['status'=>false,'error_msg'=>$address->getErrors()];
            }
            return ['status'=>false,'error_msg'=>'请用post方式提交'];
        }else{
            return ['status'=>false,'error_msg'=>'目前尚未登录，请登录'];
        }
    }

    //验证码
    public function actions(){
    return [
        'captcha'=>[
            'class'=>CaptchaAction::className(),
            'minLength'=>'4',
            'maxLength'=>'4',
        ],
    ];
    //http://www.yii2_demo.com/api/captcha.html  //获取验证码图片
        //http://www.yii2_demo.com/api/captcha.html?refresh=1  //刷新验证码
        //http://www.yii2_demo.com/api/captcha.html?v=59575cbb0a1b1   //获取新的验证码图片
}
    //图片上传
    public function actionUp(){
        //获取图片的信息
        $img=UploadedFile::getInstanceByName('img');
        if($img){
            mkdir('upload',755);
            $fileName='/upload/'.uniqid(1000,9999).'.'.$img->extension;
            $result=$img->saveAs(\Yii::getAlias('@webroot').$fileName,false);
            if($result){
                return ['status'=>false,'error_msg'=>'','data'=>$fileName];
            }else{
                return ['status'=>false,'error_msg'=>$img->error];
            }
        }else{
            return ['status'=>false,'error_msg'=>'尚未上传文件'];
        }
    }










}


?>