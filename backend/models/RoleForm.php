<?php
namespace backend\models;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{
    public $name;
    public $description;
    public $permissions=[];

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe'],//定义该字段是安全的  不需要验证
        ];
    }


    public function attributeLabels()
    {
        return [
           'name'=>'角色名称',
            'description'=>'角色描述',
            'permissions'=>'权限'
        ];
    }
    //获取所有的权限
   public static function getPermissionsOptions(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }

    //角色增加验证
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        if($authManager->getRole($this->name)){
            $this->addError('name','该角色已存在');
        }else{
            //创建角色
            $role=$authManager->createRole($this->name);
            //赋值
            $role->description=$this->description;
            if($authManager->add($role)){
                //循环遍历  关联权限
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
            }
            return true;
        }
        return false;
    }

    //角色修改
    public function updateRole($name){
        $authManager=\Yii::$app->authManager;
        if($this->name!=$name&&$authManager->getRole($this->name)){
            $this->addError('name','该角色已存在');
        }else{
            $role=$authManager->getRole($name);
            $role->name=$this->name;
            $role->description=$this->description;
            if($authManager->update($name,$role)){
                //去掉角色之前的所有权限关联
                $authManager->removeChildren($role);
                //循环遍历   关联权限
                foreach ($this->permissions as $permissionName){
                    $permission=\Yii::$app->authManager->getPermission($permissionName);
                    $authManager->addChild($role,$permission);
                }
                return true;
            }
            return false;
        }
    }

    //给要修改的角色赋值
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }

}


?>