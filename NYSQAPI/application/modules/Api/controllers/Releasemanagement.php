<?php

use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;

class ReleasemanagementController extends Yaf\Controller_Abstract
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
            echo json_encode(['code' => $code, 'message' => $this->error[$code]]);
            exit;
        } else {
            echo json_encode(['code' => $code, 'message' => $this->error[$code], "data" => $data]);
            exit;
        }
    }

    public function thematiclistAction(){
        if(!isset($this->input["zone_id"])){
            $this->ajax_return(1011);
        }
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }
        $mdData = new relesmanModel();
        $arrData = $mdData->thematiclist($this->input["zone_id"],$this->input["user_id"]);
        $this->ajax_return(200,$arrData);
    }

    public function thematicAction(){
        $mdData = new relesmanModel();
        $arrData = $mdData->thematic();
        $this->ajax_return(200,$arrData);
    }

    public function addthematicAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }
        if(!isset($this->input["zone_id"])){
            $this->ajax_return(1011);
        }
        if(!isset($this->input["title"])){
            $this->ajax_return(1010);
        }
        if(!isset($this->input["content"])){
            $this->ajax_return(1008);
        }
        if(!isset($this->input["pic"])){
            $this->ajax_return(1012);
        }
        $mdData = new relesmanModel();
        $arrData = $mdData->addthematic($this->input);
        $this->ajax_return($arrData);
    }
}
