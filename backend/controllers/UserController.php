<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\LoginForm;
use backend\models\PasswordEdit;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Request;

class UserController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $query=User::find()->where(['=','status','1']);
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2,
        ]);
        $users=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['users'=>$users,'page'=>$page]);
    }

    public function actionAdd(){
        $user=new User();
        $request=new Request();
        if($request->isPost){
            $user->load($request->post());
            //var_dump($user);exit;
            if($user->validate()){
                $user->save();
                //用户关联角色
                $authManager=\Yii::$app->authManager;
                foreach ($user->roles as $roleName){
                    $role=$authManager->getRole($roleName);
                    $authManager->assign($role,$user->id);
                }
//                var_dump($user->getErrors());exit;
                \Yii::$app->session->setFlash('sucess','添加成功！');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('add',['user'=>$user]);
    }

    public function actionEdit($id){
        $user=User::findOne(['id'=>$id]);
        $user->loadData($id);
        $request=new Request();
        if($request->isPost){
            $user->load($request->post());
            if($user->validate()){
                $user->updated_at=time();
                $user->save();
                //先删除所有的角色关联
                $authManager=\Yii::$app->authManager;
                $authManager->revokeAll($user->id);
                //var_dump($user->roles);exit;
                foreach ($user->roles as $roleName){
                    $role=$authManager->getRole($roleName);
                    $authManager->assign($role,$user->id);
                }
                \Yii::$app->session->setFlash('sucess','修改成功！');
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('add',['user'=>$user]);
    }

    public function actionDel($id){
        $user=User::findOne(['id'=>$id]);
        $user->updateAttributes(['status'=>0]);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['user/index']);
    }

    //用户登录
    public function actionLogin(){
        $model=new LoginForm();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                return $this->redirect(['user/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    //过滤器
    public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>RbacFilter::className(),
                'only'=>['add','edit','index','del'],//该过滤器作用的操作 ，默认是所有操作
//                'rules'=>[
//                    [//未认证用户允许执行view操作
//                        'allow'=>true,//是否允许执行
//                        'actions'=>['login'],//指定操作
//                        'roles'=>['?'],//角色？表示未认证用户  @表示已认证用户
//                    ],
//                    [//已认证用户允许执行add操作
//                        'allow'=>true,//是否允许执行
//                        'actions'=>['add','del','edit','index','login','logout'],//指定操作
//                        'roles'=>['@'],//角色？表示未认证用户  @表示已认证用户
//                    ],
//
//                    //其他都禁止执行
//
//                ]
            ],
        ];
    }


    //注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        $this->redirect(['user/login']);
    }

    public function actionPasswordEdit(){
        $model=new PasswordEdit();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $user=\Yii::$app->user->identity;
                $user->password_hash=\Yii::$app->security->generatePasswordHash($model->newPassword);

                if($user->save(false)){
                    \Yii::$app->session->setFlash('success','密码修改成功');
                    return $this->redirect(['user/index']);
                }else{
                    var_dump($user->getErrors());exit;
                }
            }
        }
        return $this->render('edit',['model'=>$model]);
    }
        //验证码
    public function actions(){
        return [
            'captcha' => [
                'class' =>'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,//验证码最小长度
                'maxLength'=>4,//最大长度
            ],
        ];
    }



}
