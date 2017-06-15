<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model {
    public $username;
    public $password_hash;
    public $code;
    public $rememberMe;

        public function rules()
        {
            return [
                [['username','password_hash'],'required'],
                ['username','validateCheck'],
                ['code','captcha','captchaAction'=>'user/captcha'],
                ['rememberMe','boolean'],
            ];
        }

        public function attributeLabels()
        {
            return [
                'username'=>'账号',
                'password_hash'=>'密码',
                'rememberMe'=>'记住我'
            ];
        }

        public function validateCheck(){
            $user=User::findOne(['username'=>$this->username]);
            if($user){
                if(\Yii::$app->security->validatePassword($this->password_hash,$user->password_hash)){
                        $duration=$this->rememberMe?7*24*3600:0;
                        \Yii::$app->user->login($user,$duration);
                    $user->last_time=time();
                    $user->last_ip=\Yii::$app->request->userIP;
                    $user->save();
                }else{
                    $this->addError('password_hash','账号或者密码不存在');
                }
            }else{
                $this->addError('username','账号或者密码不存在');
            }
        }
}


?>