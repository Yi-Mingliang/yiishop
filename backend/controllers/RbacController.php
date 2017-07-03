<?php
namespace backend\controllers;
use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends BackendController {
    //权限的增删改查
        //添加权限
            public function actionAddPermission(){
               $model=new PermissionForm();
               if($model->load(\Yii::$app->request->post()) && $model->validate()){
                   if($model->addPermission()){
                       \Yii::$app->session->setFlash('success','添加成功！');
                       return $this->redirect(['index-permission']);
                   }
               }
                return $this->render('add-permission',['model'=>$model]);
            }
            //修改权限
            public function actionEditPermission($name){
                $permission=\Yii::$app->authManager->getPermission($name);
                if($permission==null){
                    throw new NotFoundHttpException('该权限不存在');
                }
                $model=new PermissionForm();
                //将要修改权限的值赋值给表单
                $model->loadData($permission);
                if($model->load(\Yii::$app->request->post())&& $model->validate()){
                    if($model->updatePermission($name)){
                        \Yii::$app->session->setFlash('success','权限修改成功！');
                        return $this->redirect(['index-permission']);
                    }
                }
                return $this->render('add-permission',['model'=>$model]);

            }
            //删除权限
            public function actionDelPermission($name){
                $permission=\Yii::$app->authManager->getPermission($name);
                if ($permission==null){
                    throw new NotFoundHttpException('该权限不存在');
                }
                \Yii::$app->authManager->remove($permission);
                \Yii::$app->session->setFlash('success','删除成功！');
                return $this->redirect(['index-permission']);
            }
            //权限列表
            public function actionIndexPermission(){
                $permissions=\Yii::$app->authManager->getPermissions();
                return $this->render('index-permission',['permissions'=>$permissions]);
            }


    //角色的增删改查
        //添加角色
        public function actionAddRole(){
                $model=new RoleForm();
                if($model->load(\Yii::$app->request->post()) && $model->validate()){
                    if($model->addRole()){
                        \Yii::$app->session->setFlash('success','角色添加成功');
                        return $this->redirect(['index-role']);
                    }
                }
                return $this->render('add-role',['model'=>$model]);
        }
        //修改角色
        public function actionEditRole($name){
            $role=\Yii::$app->authManager->getRole($name);
            if($role==null){
                throw new NotFoundHttpException('该角色不存在');
            }
            $model=new RoleForm();
            $model->loadData($role);
            if($model->load(\Yii::$app->request->post())&& $model->validate()){
                if($model->updateRole($name)){
                    \Yii::$app->session->setFlash('success','角色修改成功');
                    return $this->redirect(['index-role']);
                }

            }
            return $this->render('add-role',['model'=>$model]);
        }
        //删除角色
    public function actionDelRole($name){
            $role=\Yii::$app->authManager->getRole($name);
            if($role==null){
                throw new NotFoundHttpException('该角色不存在');
            }
        if(\Yii::$app->authManager->remove($role)){
            \Yii::$app->session->setFlash('success','删除成功！');
            return $this->redirect(['index-role']);
        }

    }
        //角色列表
        public function actionIndexRole(){
            $roles=\Yii::$app->authManager->getRoles();
            return $this->render('index-role',['roles'=>$roles]);
        }


    /*
 * 给用户关联角色的方法
 */
    public function actionUser()
    {
        $authManager = \Yii::$app->authManager;
        //获取所有角色
        $authManager->getRoles();
        //将id为1的用户，添加管理员角色
        $role= $authManager->getRole('管理员');
        $authManager->assign($role,1);

        //修改用户关联的角色
        //去掉当前用户的所以关联角色
        $authManager->revokeAll(1);

    }



}





?>