<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property integer $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    public $rePassword;
    public $checkCode;
    public $code;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required','message'=>'{attribute}不能为空'],
//            [['last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username'], 'string', 'max' => 50],
           [['username'], 'unique','message'=>'该用户已存在'],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'email','message'=>'必须是合法的邮箱'],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11],
            ['rePassword','compare','compareAttribute'=>'password_hash','message'=>'两次输入的密码不一致'],
//            ['checkCode','captcha','message'=>'验证码不正确'],
//            ['code','validateCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名 :',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码 :',
            'rePassword' => '确认密码 :',
            'email' => '邮箱 :',
            'tel' => '手机号码 :',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
            'checkCode' => '验证码',
            'code' => '短信验证码',
            'rememberMe' => '记住我',
        ];
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at=time();
            $this->updated_at=time();
            $this->password_hash=Yii::$app->security->generatePasswordHash($this->password_hash);
            $this->auth_key=Yii::$app->security->generateRandomString();
        }

        return parent::beforeSave($insert);
    }


    public function recheck(){
        $member=self::findOne(['username'=>$this->username]);
        if($member){
            $this->addError('username','该用户已存在');
            return false;
        }else{
            return true;
        }
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
       return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()==$authKey;
    }

    public function validateCode(){
        $v=Yii::$app->cache->get('tel_'.$this->tel);
        //判断缓存里是否存在
        if(!$v  || $v!=$this->code){
            $this->addError('code','手机验证码不正确');
        }
    }


}
