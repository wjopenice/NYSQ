<?php

use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;

class MapController extends Yaf\Controller_Abstract
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

    public function cityscaleAction(){
        $res['cityname'] = "深圳";
        $res['mapmark'] = "南油服装市场";
        $bd_num = (int)$this->db->zscount("business_district");
        $merc_num = (int)$this->db->zscount("merc");
        $res['bd_num'] = "商圈".$bd_num;
        $res['merc_num'] = "商家".$merc_num;
        $res['longitude'] = 113.932453;
        $res['latitude'] = 22.516268;
        $this->ajax_return(200,$res);
    }

    public function areascaleAction(){
        $bddata = new businessdistrictModel();
        $res = $bddata->getbd();
        $this->ajax_return(200,$res);
    }

    public function bdfindAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }
        if(!isset($this->input["b_d_id"])){
            $this->ajax_return(1001);
        }
        $user_id = $this->input["user_id"];
        $b_d_id = $this->input["b_d_id"];
        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $bddata = new businessdistrictModel();
        $res['b_d_info'] = $bddata->getbdfind($b_d_id);
        $res['merc_list'] = $bddata->getmerc($user_id,$b_d_id,$start,$showPage);
        $this->ajax_return(200,$res);
    }
}
