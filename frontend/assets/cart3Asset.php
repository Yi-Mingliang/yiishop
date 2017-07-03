<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class Cart3Asset extends AssetBundle
{
    public $basePath = '@webroot';//静态资源的硬盘路径
    public $baseUrl = '@web';//静态资源的url路径
    //需要加载的css文件
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/success.css',
        'style/footer.css',
    ];
///<link rel="stylesheet" href="style/base.css" type="text/css">
//<link rel="stylesheet" href="style/global.css" type="text/css">
//<link rel="stylesheet" href="style/header.css" type="text/css">
//<link rel="stylesheet" href="style/success.css" type="text/css">
//<link rel="stylesheet" href="style/footer.css" type="text/css">
    //需要加载的js文件
    public $js = [

    ];
    //和其他静态资源管理器的依赖关系
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}