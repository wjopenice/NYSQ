<?php
use Yaf\Application;
use Yaf\Dispatcher;
class BaseGetController extends Yaf\Controller_Abstract
{
    public $db;
    public $session;

    public function init()
    {
        Yaf\Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $this->db = new dbModel();
        $this->session =  Yaf\Session::getInstance();
    }

    public function status_nodata(){
        $result['code'] = 20000;
        $result['msg'] = 'success';
        echo json_encode($result,320);
        exit;
    }

    public function status($res){
        $result['code'] = 20000;
        $result['msg'] = 'success';
        $result['data'] = $res;
        echo json_encode($result,320);
        exit;
    }

    public function error($res,$code=20000){
        $result['code'] = $code;
        $result['msg'] = 'error';
        $result['data'] = $res;
        echo json_encode($result,320);
        exit;
    }

    //单文件上传
    public function uploadone($file,$path){
        $time = time();
        $dir = APP_PATH."/public/".$path."/".$time;
        if(!file_exists($dir)){
            mkdir($dir,0777,true);
        }
        $pathicon = $dir."/".$file['name'];
        move_uploaded_file( $file['tmp_name'],$pathicon);

        $fileArr = "/public/".$path."/".$time."/".$file['name'];
        return $fileArr;
    }

    //二维码生成器
    public function qrcode($inser_info){
        include APP_PATH."/vendor/phpqrcode/phpqrcode.php";
        $url = "/public/qrcode/".time().rand(000,999).".png";
        $path = APP_PATH.$url;
        @chmod($path,0777);
        QRcode::png($inser_info,$path,"L",6,1);
        return $url;
    }
}
