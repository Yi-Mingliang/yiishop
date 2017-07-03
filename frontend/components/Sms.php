<?php
namespace frontend\components;

use yii\base\Component;
use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;

class Sms extends Component{
    public $app_key;
    public $app_secret;
    public $sign_name;
    public $template_code;
    private $_num;
    private $_param=[];


    //设置手机号码
    public function setNum($tel){
        $this->_num=$tel;
        return $this;
    }
    //设置短信验证内容
    public function setParam(array $param){
        $this->_param=$param;
        return $this;
    }
    //设置签名名字  必须通过审核的
    public function setSign($sign){
        $this->sign_name=$sign;
        return $this;
    }
    //设置模板内容
    public function setTemplage($id){
        $this->template_code=$id;
        return $this;
    }


    public function send(){
        $client = new Client(new App(['app_key'=> $this->app_key,
            'app_secret'=>$this->app_secret]));
        $req = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum($this->_num)
            ->setSmsParam($this->_param)
            ->setSmsFreeSignName($this->sign_name)
            ->setSmsTemplateCode($this->template_code);

        return $client->execute($req);
    }

}


?>