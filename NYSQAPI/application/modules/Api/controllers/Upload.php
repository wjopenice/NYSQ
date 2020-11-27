<?php
use Yaf\Application;
use Yaf\Dispatcher;
class UploadController extends Yaf\Controller_Abstract {
    public $db;
    public function init(){
        $this->db = new dbModel();
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token');
        header('Access-Control-Allow-Method: POST, GET');//允许访问的方式
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
            exit;
        }
    }
    //文件上传
    public function indexAction() {
        $key = array_keys($_FILES)[0];
        if(!empty($_FILES[$key])){
            if(is_array($_FILES[$key]['name'])){
                $res = $this->uploadss($_FILES[$key],"upload");
            }else{
                $res = $this->uploadone($_FILES[$key],"upload");
            }
        }else{
            $res = ['msg'=>"上传失败"];
        }
        $result['code'] = 200;
        $result['msg'] = 'success';
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
    //多文件上传
    public function uploadss($file,$path){
        $time = time();
        $dir = APP_PATH."/public/".$path."/".$time;
        if(!file_exists($dir)){
            mkdir($dir,0777,true);
        }
        $fileArr = [];
        for($i=0;$i<count($file['name']);$i++){
            $pathicon = $dir."/".$file['name'][$i];
            move_uploaded_file( $file['tmp_name'][$i],$pathicon);
            $fileArr[] = "/public/".$path."/".$time."/".$file['name'][$i];
        }
        $filedata = json_encode($fileArr,320);
        return $filedata;
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
