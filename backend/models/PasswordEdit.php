<?php
namespace backend\models;

use yii\base\Model;

class PasswordEdit extends Model{
   public $oldPassword;
   public $newPassword;
   public $rePassword;

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
           'rePassword'=>'确认密码'
       ];
   }


   public function validateCheck(){
       $password_hash=\Yii::$app->user->identity->password_hash;
       if(\Yii::$app->security->validatePassword($this->oldPassword,$password_hash)){

       }else{
           $this->addError('oldPassword','原密码不正确');
       }
   }
}


?>