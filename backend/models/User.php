<?php

namespace backend\models;

use Symfony\Component\Yaml\Yaml;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $roles=[];

    static public $statusOptions=[1=>'正常',2=>'删除'];
    //获取所有的角色
    public static function getRoles(){
        $authManager=Yii::$app->authManager;
        return ArrayHelper::map($authManager->getRoles(),'name','description');
    }

    //获取要修改的角色
    public function loadData($id){
        $roles=Yii::$app->authManager->getRolesByUser($id);
        foreach ($roles as $role){
            $this->roles[]=$role->name;
        }
    }

    public function beforeSave($insert)
    {
        if($insert){
            $this->created_at=time();
            //任意生成字符串
            $this->auth_key=Yii::$app->security->generateRandomString();
            $this->password_hash=Yii::$app->security->generatePasswordHash( $this->password_hash);
        }
        $this->updated_at=time();
        return parent::beforeSave($insert);
    }
//
//    //定义场景常量
//    const SCENARIO_ADD ='add';
//    const SCENARIO_EDIT ='edit';
//    //定义场景字段属性
//    public function scenarios()
//    {
//        $scenario= parent::scenarios();
//        $scenario[self::SCENARIO_ADD]=['username','password_hash','status','email'];
//        $scenario[self::SCENARIO_EDIT]=['username','status','email'];
//        return $scenario;
//    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash', 'email'], 'required'],
            ['status', 'integer'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            ['email','email'],
            ['roles','safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '账号',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'roles'=>'角色'
        ];
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
        // TODO: Implement validateAuthKey() method.
    }
}
