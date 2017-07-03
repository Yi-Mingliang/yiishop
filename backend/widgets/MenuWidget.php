<?php
namespace backend\widgets;


use backend\models\Menu;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;
class MenuWidget extends Widget{
    //widget 被实例化执行的代码
    public function init()
    {
        parent::init();
    }

    //widget 被调用时执行的代码
    public function run()
    {
        NavBar::begin([
            'brandLabel' => '京西商城系统',
            'brandUrl' => \Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => '首页', 'url' => ['user/index']],
        ];
        if (Yii::$app->user->isGuest) {
            $menuItems [] =
                ['label' => '登录', 'url' => Yii::$app->user->loginUrl];
        } else {
            //获取所有的一级菜单
            $menus=Menu::findAll(['parent_id'=>0]);
            foreach ($menus as $menu){
               $Item=['label'=>$menu->name,'items'=>[]];
               foreach ($menu->children as $child){
                   if(Yii::$app->user->can($child->url)){
                       $Item['items'][]=['label'=>$child->name,'url'=>[$child->url]];
                   }
               }
               //如果一级菜单因为权限导致没有子菜单   就不显示
                if(!empty($Item['items'])){
                    $menuItems[]=$Item;
                }

            }
//            $menuItems[] = '<li>'
//                . Html::beginForm(['/user/logout'], 'post')
//                . Html::submitButton(
//                    '注销 (' . Yii::$app->user->identity->username . ')',
//                    ['class' => 'btn btn-link logout']
//                )
//                . Html::endForm()
//                . '</li>';
            $menuItems[]=['label' => '注销('.Yii::$app->user->identity->username.')', 'url' => ['user/logout']];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $menuItems,
        ]);
        NavBar::end();
    }


}
?>