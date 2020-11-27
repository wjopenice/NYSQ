<?php

use Yaf\Application;
use Yaf\Dispatcher;
class ImgController extends BaseGetController
{
    public $db;

    public function init()
    {
        \Yaf\Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $this->db = new dbModel();
    }

    public function addAction(){
        if($this->getRequest()->isPost()){
            $img = new imgModel();
            $res = $img->upload();
            if(!empty($res)){
                $this->status($res);
            }else{
                $this->error('图片上传失败');
            }
        }
        $this->getView()->display('img/img.phtml');
    }

    public function imgListAction(){
        if(empty($_GET['type'])) $this->error('参数type不能为空');
        $sql = "select id,name,url from ny_img where `type`=? ";
        $list = $this->db->preprocessing($sql,[$_GET['type']]);
        echo json_encode($list);
    }

}
