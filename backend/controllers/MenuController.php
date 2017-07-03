<?php
namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class MenuController extends BackendController
{
    //添加菜单
    public function actionAdd(){
        $model=new Menu();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            if($model->addMenu()){
                $model->save();
                \Yii::$app->session->setFlash('success','菜单添加成功');
                return $this->redirect(['menu/index']);
            }
        }
        $data=ArrayHelper::merge([['id'=>0,'name'=>'顶级菜单','parent_id'=>'0']],Menu::find()->where(['=','parent_id','0'])->all());
        //var_dump($data);exit;
        return $this->render('add',['model'=>$model,'data'=>$data]);
    }
    //修改菜单
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())&&$model->validate()){
            if($model->editMenu()){
                $model->save();
                \Yii::$app->session->setFlash('success','菜单修改成功');
                return $this->redirect(['menu/index']);
            }
        }
        $data=ArrayHelper::merge([['id'=>0,'name'=>'顶级菜单','parent_id'=>'0']],Menu::find()->where(['=','parent_id','0'])->all());
        //var_dump($data);exit;
        return $this->render('add',['model'=>$model,'data'=>$data]);
    }
    //删除菜单
    public function actionDel($id){
        $menu=Menu::findOne(['id'=>$id]);
        if($menu->delMenu()){
            $menu->delete();
            \Yii::$app->session->setFlash('success','菜单删除成功');
            return $this->redirect(['menu/index']);
        }
    }
    //显示菜单
    public function actionIndex(){
        $query=Menu::find();
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>20,
        ]);
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
}


?>