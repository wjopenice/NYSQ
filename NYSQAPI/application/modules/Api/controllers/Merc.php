<?php
use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;
class MercController extends Yaf\Controller_Abstract{

    public $db;
    public $input;
    public $get;
    public $error;

    public function init(){
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token");
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
        $this->error = (new CodeConfigModel())->getCodeConfig();
        $this->db = new dbModel();
        $this->input = json_decode(file_get_contents("php://input"),true);
        $this->get = $_GET;
    }

    public function ajax_return($code,$data = null){
        if(is_null($data)){
            echo json_encode(['code'=>$code,'message'=>$this->error[$code]]);exit;
        }else{
            echo json_encode(['code'=>$code,'message'=>$this->error[$code],"data"=>$data]);exit;
        }
    }

    public function indexAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }

        if(!isset($this->input["merc_id"])){
            $this->ajax_return(1003);
        }
        $mercdb = new mercModel();
        $arrData = $mercdb->getinfo($this->input["user_id"],$this->input["merc_id"]);
        $this->ajax_return(200,$arrData);
    }

    public function shoplistAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }

        if(!isset($this->input["merc_id"])){
            $this->ajax_return(1003);
        }

        if(!isset($this->input["type"])){
            $this->ajax_return(1005);
        }
        $user_id = $this->input["user_id"];
        $merc_id = $this->input["merc_id"];
        $type = $this->input["type"];

        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $mercdb = new mercModel();
        $arrData = $mercdb->getShopList($user_id,$merc_id,$type,$start,$showPage);
        $this->ajax_return(200,$arrData);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
