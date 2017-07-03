<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{
    public $username;
    public $password_hash;
    public $checkCode;
    public $rememberMe;

    //定义一个常量
    const SCENARIO_DEFAULT ='default_code';
    const SCENARIO_API ='api_code';


    public function rules()
    {
        return [
            [['username','password_hash'],'required'],
            ['checkCode','captcha','message'=>'验证码不正确','captchaAction'=>'api/captcha','on'=>self::SCENARIO_API],//接口验证码验证
            ['checkCode','captcha','message'=>'验证码不正确','captchaAction'=>'member/login','on'=>self::SCENARIO_DEFAULT],
            ['rememberMe','boolean'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password_hash'=>'密码',
            'checkCode' => '验证码',
            'rememberMe' => '记住我',
        ];
    }

    public function check(){
        $member=Member::findOne(['username'=>$this->username]);
        if($member){
            if(\Yii::$app->security->validatePassword($this->password_hash,$member->password_hash)){
                $duration=$this->rememberMe?7*24*3600:0;
                \Yii::$app->user->login($member,$duration);
                $member->updated_at=time();
                $member->last_login_time=time();
                $member->last_login_ip=ip2long(\Yii::$app->request->userIP);
                $member->save(false);
                return true;
            }else{
                $this->addError('username','用户名或密码不正确');
                return false;
            }
        }else{
            $this->addError('username','用户名或密码不正确');
            return false;
        }
    }





}


?>