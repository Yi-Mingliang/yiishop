<?php
namespace backend\components;

use yii\base\ActionFilter;
use yii\web\HttpException;

class RbacFilter extends ActionFilter{

    public function beforeAction($action)
    {
        $user=\Yii::$app->user;
        if(!$user->can($action->uniqueId)){
            //判断用户是否登录
            if($user->isGuest){
                return $action->controller->redirect($user->loginUrl);
            }
            throw  new HttpException('403','对不起！你没有访问该网页的权限！');
            return false;
        }
        return parent::beforeAction($action);
    }


}


?>