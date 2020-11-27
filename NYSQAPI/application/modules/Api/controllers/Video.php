<?php

use Qiniu\Auth;
use Yaf\Application;
use Yaf\Dispatcher;
class VideoController extends BaseGetController
{
    private $accessKey;
    private $secretKey;
    private $bucket;
    public $url = 'http://up-z2.qiniup.com';

    public function init()
    {
        parent::init();
        $qiniu = Application::app()->getConfig()->qiniu;
        $this->accessKey = $qiniu['accessKey'];
        $this->secretKey = $qiniu['secretKey'];
        $this->bucket = $qiniu['bucket'];
    }

    public function addAction(){
        include APP_PATH.'/vendor/qiniu/php-sdk/autoload.php';
        if(IS_POST){
            $auth = new Auth($this->accessKey, $this->secretKey);
            $token = $auth->uploadToken($this->bucket);
            $map = [
              'file'=>$_FILES['file'],
              'token'=> $token
            ];
            $res = _httpPost($this->url,$map);
        }
    }
}
