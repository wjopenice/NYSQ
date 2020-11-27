<?php

use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;

class MercadminController extends Yaf\Controller_Abstract
{
    public $db;
    public $input;
    public $get;
    public $error;

    public function init()
    {
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $this->error = (new CodeConfigModel())->getCodeConfig();
        $this->db = new dbModel();
        $this->input = json_decode(file_get_contents("php://input"), true);
        $this->get = $_GET;
    }

    public function ajax_return($code, $data = null)
    {
        if (is_null($data)) {
            echo json_encode(['code' => $code, 'message' => $this->error[$code]]);exit;
        } else {
            echo json_encode(['code' => $code, 'message' => $this->error[$code], "data" => $data]);exit;
        }
    }

    public function indexAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }
        $m_id = empty($_GET['m_id']) ? NULL : $_GET['page'];
        $user_id = $this->input["user_id"];
        $mdData = new mercviceModel();
        $user_info = $mdData->getuserinfo($user_id,$m_id);
        if(!empty($user_info)){
            $res['user_info'] = $user_info;
            $res['setting_cion'] = $mdData->icon();
            $res['novice_opening_mission'] = (object)[];
            $this->ajax_return(200,$res);
        }else{
            $this->ajax_return(1007);
        }
    }

    public function systagAction(){
        $mdData = new mercviceModel();
        $systag = $mdData->switchtag(1);
        $this->ajax_return(200,$systag);
    }

    public function diytagAction(){
        $mdData = new mercviceModel();
        $systag = $mdData->switchtag(2);
        $this->ajax_return(200,$systag);
    }

    public function addsystagAction(){
        if(!isset($this->input["name"])){
            $this->ajax_return(1008);
        }
        $mdData = new mercviceModel();
        $status = $mdData->addswitchtag($this->input["name"],1);
        $this->ajax_return($status);
    }

    public function adddiytagAction(){
        if(!isset($this->input["name"])){
            $this->ajax_return(1008);
        }
        $mdData = new mercviceModel();
        $status = $mdData->addswitchtag($this->input["name"],2);
        $this->ajax_return($status);
    }

    public function addmercAction(){
        if(!isset($this->input["b_d_id"])){
            $this->ajax_return(1001);
        }
        if(!isset($this->input["merc_name"])){
            $this->ajax_return(1013);
        }
        if(!isset($this->input["merc_logo"])){
            $this->ajax_return(1014);
        }
        if(!isset($this->input["merc_info"])){
            $this->ajax_return(1015);
        }
        if(!isset($this->input["merc_description"])){
            $this->ajax_return(1016);
        }
        if(!isset($this->input["merc_floor"])){
            $this->ajax_return(1017);
        }
        if(!isset($this->input["merc_address"])){
            $this->ajax_return(1018);
        }
        if(!isset($this->input["room_number"])){
            $this->ajax_return(1019);
        }
        if(!isset($this->input["merc_sys_tag"])){
            $this->ajax_return(1020);
        }
        if(!isset($this->input["merc_diy_tag"])){
            $this->ajax_return(1021);
        }
        if(!isset($this->input["merc_tel"])){
            $this->ajax_return(1022);
        }
        if(!isset($this->input["merc_wechat_qrcode"])){
            $this->ajax_return(1023);
        }
        $mdData = new mercviceModel();
        $status = $mdData->addswitchtag($this->input);
        $this->ajax_return($status);
    }
}
