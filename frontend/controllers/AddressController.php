<?php
namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;
use yii\web\Controller;

class AddressController extends Controller{

    public $layout ='order';

    public function actionIndex(){
        $address=new Address();
//        var_dump($address);exit;
        $member_id=\Yii::$app->user->identity->id;
        $models=Address::find()->where(['=','member_id',$member_id])->all();
        if($address->load(\Yii::$app->request->post())&& $address->validate()){
            if($address->status){
                foreach ($models as $model){
                    $model->status=0;
                    $model->save();
                }
                $address->member_id=$member_id;
                $address->save();
                return $this->redirect(['index']);
            }
        }
        return $this->render('index',['address'=>$address,'models'=>$models]);
    }

    public function actionEdit($id){
        $address=Address::findOne($id);
        $models=Address::find()->all();
        if($address->load(\Yii::$app->request->post())&& $address->validate()){
            if($address->status){
               foreach ($models as $model){
                   $model->status=0;
                   $model->save();
               }
            }
            $address->save();
            return $this->redirect(['index']);
        }
        return $this->render('index',['address'=>$address,'models'=>$models]);
    }


    public function actionDefault($id){
        $model=Address::findOne($id);
        $addresss=Address::find()->all();
        foreach ( $addresss as $address){
            $address->status=0;
            $address->save();
        }
        $model->updateAttributes(['status'=>1]);
        return $this->redirect(['index']);
    }


    public function actionDel($id){
        Address::findOne($id)->delete();
        return $this->redirect(['index']);
    }


    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
            'class'=>\chenkby\region\RegionAction::className(),
            'model'=>Locations::className(),
        ];
        return $actions;
    }

}




?>