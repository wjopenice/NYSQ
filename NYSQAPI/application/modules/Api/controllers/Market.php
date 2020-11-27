<?php
use Yaf\Application;
use Yaf\Dispatcher;
use Error\CodeConfigModel;
class MarketController extends Yaf\Controller_Abstract{

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

    public function marketlistAction(){
        $bdmodel = new businessdistrictModel();
        $result = $bdmodel->getList();
        $this->ajax_return(200,$result);
    }

    public function marketfloorAction(){
        if(empty($this->input)){
            $this->ajax_return(1000);
        }else{
            $id = $this->input['id'];
            $bdmodel = new businessdistrictModel();
            $result = $bdmodel->getfloor($id);
            if(!is_null($result)){
                $this->ajax_return(200,$result);
            }else{
                $this->ajax_return(1001);
            }
        }
    }

    public function markettagAction(){
        $bdmodel = new tagModel();
        $result['sys'] = $bdmodel->sysList();
        $result['merc'] = $bdmodel->mercList();
        $this->ajax_return(200,$result);
    }

    public function marketrecomAction(){
        $user_id = $this->input['user_id'];
        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $bdmodel = new mercrecomModel();
        $result = $bdmodel->recomList($start,$showPage,$user_id);
        $this->ajax_return(200,$result);
    }

    public function marketcollectAction(){
        $user_id = $this->input['user_id'];
        if(empty($user_id)){
            $this->ajax_return(1002);
        }
        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $bdmodel = new mercusercollectModel();
        $result = $bdmodel->getList($start,$showPage,$user_id);
        $this->ajax_return(200,$result);
    }

    public function linkageAction(){
        $currentPage = empty($this->input["page"])?"1":$this->input["page"];
        $showPage = empty($this->input["showpage"])?"9":$this->input["showpage"];
        $start =  ($currentPage-1)*$showPage;
        $mercdata = new mercModel();
        $map = " M.status = 1 ";
        //区域
        if(isset($this->input["region_id"])){
            $map .= " AND B.id = {$this->input["region_id"]} ";
            unset($this->input["region_id"]);
        }
        //楼层
        if(isset($this->input["floor_id"])){
            $map .= " AND B.b_d_floor IN ({$this->input["floor_id"]}) ";
            unset($this->input["floor_id"]);
        }
        //标签筛选
        if(isset($this->input["filter_id"])){
            $tags = $this->input['filter_id'];
            $map .= " AND ((M.merc_sys_tag IN ({$tags})) OR (M.merc_diy_tag IN ({$tags})))";
            unset($this->input["filter_id"]);
        }
        //收藏
        if(isset($this->input["user_id"])){
            $user = $this->input["user_id"];
            unset($this->input["user_id"]);
            $result = $mercdata->getAllList($start,$showPage,$map,$user);
        }else{
            $result = $mercdata->getAllList($start,$showPage,$map);
        }
        $this->ajax_return(200,$result);
    }

    public function collectAction(){
        if(!isset($this->input["user_id"])){
            $this->ajax_return(1002);
        }

        if(!isset($this->input["merc_id"])){
            $this->ajax_return(1003);
        }

        $dbcollect = new mercusercollectModel();
        $result = $dbcollect->ischeck($this->input["user_id"],$this->input["merc_id"]);
        if($result){
            $this->ajax_return(200);
        }else{
            $this->ajax_return(500);
        }
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
