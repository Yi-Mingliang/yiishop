<?php
namespace frontend\models;

use yii\base\Model;

class PasswordForm extends Model{
    public $oldpassword;
    public $newpassword;
    public $repassword;


    public function rules()
    {
        return [
            [['oldPassword','newPassword','rePassword'],'required'],
            ['oldPassword','validateCheck'],
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'两次密码不一致']
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'原密码',
            'newPassword'=>'新密码',
            'rePassword'=>'旧密码',
        ];
    }


    public function validateCheck(){

    }

}


?>