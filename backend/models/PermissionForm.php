<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{
    public $name;
    public $description;
    public function rules()
    {
        return [
            [['name','description'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'权限描述',
        ];
    }
    //添加权限的验证
    public function addPermission(){
        $authManager=\Yii::$app->authManager;
        //判断权限名称是否存在
        if($authManager->getPermission($this->name)){
            $this->addError('name','该用户已存在！');
        }else{
            //创建权限
            $permisson=$authManager->createPermission($this->name);
            //var_dump($permisson);exit;
            $permisson->description=$this->description;
            $permisson->createdAt=time();
            $permisson->updatedAt=time();
            //添加权限
            return $authManager->add($permisson);
        }
        return false;
    }

    //将要修改的权限的值赋值给表单模型
   public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }


    //修改权限的验证
    public function updatePermission($name){
        $authManager=\Yii::$app->authManager;
        //获取要修改的权限对象
        $permission=$authManager->getPermission($name);
        //判断name是否被修改   被修改  在判断修改后的名称是否已经存在
        if($this->name!=$name && $authManager->getPermission($this->name)){
            $this->addError('name','该权限已经存在');
        }else{
            //赋值
            $permission->description=$this->description;
            $permission->name=$this->name;
            //保存到数据库
            return $authManager->update($name,$permission);
        }
            return false;
    }



}


?>