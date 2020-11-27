<?php
use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;
class ShopController extends Yaf\Controller_Abstract{

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

        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }

        $arrdb = new shopModel();
        $arrData = $arrdb->getinfo($this->input["user_id"],$this->input["shop_id"]);
        $this->ajax_return(200,$arrData);
    }

    public function addindexAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["p_name"])){
            $this->ajax_return(1024);
        }
        if(!isset($this->input["p_description"])){
            $this->ajax_return(1025);
        }
        if(!isset($this->input["p_pic"])){
            $this->ajax_return(1026);
        }
        if(!isset($this->input["p_tag"])){
            $this->ajax_return(1027);
        }
        $shopmd = new shopModel();
        $asstatus = $shopmd->addshop($this->input);
        $this->ajax_return($asstatus);
    }

    public function editindexAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        if(!isset($this->input["p_name"])){
            $this->ajax_return(1024);
        }
        if(!isset($this->input["p_description"])){
            $this->ajax_return(1025);
        }
        if(!isset($this->input["p_pic"])){
            $this->ajax_return(1026);
        }
        if(!isset($this->input["p_tag"])){
            $this->ajax_return(1027);
        }
        $shopmd = new shopModel();
        $asstatus = $shopmd->editshop($this->input);
        $this->ajax_return($asstatus);
    }

    public function ststusshoplistAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["status"])){
            $this->ajax_return(1029);
        }
        $shopmd = new shopModel();
        $result = $shopmd->statusshoplist($this->input["m_id"],$this->input["status"]);
        $this->ajax_return(200,$result);
    }

    public function newestAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        $shopmd = new shopModel();
        $code = $shopmd->newest($this->input["m_id"],$this->input["shop_id"]);
        $this->ajax_return($code);
    }

    public function explosionAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        $shopmd = new shopModel();
        $code = $shopmd->explosion($this->input["m_id"],$this->input["shop_id"]);
        $this->ajax_return($code);
    }

    public function dumpAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        $shopmd = new shopModel();
        $code = $shopmd->shelves($this->input["m_id"],$this->input["shop_id"]);
        $this->ajax_return($code);
    }

    public function shelvesAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        $shopmd = new shopModel();
        $code = $shopmd->shelves($this->input["m_id"],$this->input["shop_id"]);
        $this->ajax_return($code);
    }

    public function removeAction(){
        if(!isset($this->input["m_id"])){
            $this->ajax_return(1003);
        }
        if(!isset($this->input["shop_id"])){
            $this->ajax_return(1006);
        }
        $shopmd = new shopModel();
        $code = $shopmd->shelves($this->input["m_id"],$this->input["shop_id"]);
        $this->ajax_return($code);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
