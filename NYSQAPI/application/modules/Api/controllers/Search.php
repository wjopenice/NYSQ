<?php
use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;
class SearchController extends Yaf\Controller_Abstract{

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

    public function logAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }
        $search = new searchModel();
        $result['his'] = $search->getUserLog($this->input["user_id"]);
        $result['hot'] = $search->getLog();
        $this->ajax_return(200,$result);
    }

    public function findAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }

        if(!isset($this->input["search"])){
            $this->ajax_return(1004);
        }

        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $seaData = new searchModel();
        $result = $seaData->getList($this->input["user_id"],$this->input["search"],$start,$showPage);
        $this->ajax_return(200,$result);
    }


    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
