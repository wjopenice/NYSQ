<?php
use Yaf\Application;
use Yaf\Dispatcher;
class BusinessdistrictController extends BaseController
{
    public $db;

    public function init()
    {
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token');
        header('Access-Control-Allow-Method: POST, GET');//允许访问的方式
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            exit;
        }
        $this->db = new dbModel();
    }

    public function status($res)
    {
        $result['code'] = 20000;
        $result['msg'] = 'success';
        $result['data'] = $res;
        echo json_encode($result, 320);
        exit;
    }

    public function typeAction(){
        $result = $this->db->field("id,name")
            ->table("ny_operator")
            ->select();
        $res['items'] = $result;
        $this->status($res);
    }

    public function adddbAction(){
        $data = $_GET;
        $lbs = explode(",",$_GET['b_d_longlat']);
        unset($data['b_d_longlat']);
        $data['b_d_longitude'] = $lbs[0];
        $data['b_d_latitude'] = $lbs[1];
        $data['b_d_creata_time'] = time();
        $data['b_d_sort'] = 0;
        $bool = $this->db->action($this->db->insertSql("business_district",$data));
        if($bool){
            $res = 'success';
        }else{
            $res = 'error';
        }
        $this->status($res);
    }

    public function listAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_business_district")
                ->where("b_d_name like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("business_district","*","total","b_d_name like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_business_district")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("business_district");
        }

        foreach ($arr as $k=>$v){
            $operator = $this->db->field("id,name")
                ->table("ny_operator")
                ->where("id = {$v['op_id']}")
                ->find();
            $arr[$k]['op_id'] = $operator['name'];
            $arr[$k]['b_d_floor'] = explode(",",$v['b_d_floor']);
            $arr[$k]['b_d_logo'] = APP_URI.$v['b_d_logo'];
        }

        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function removeAction(){
        $id = get("id");
        $this->db->action($this->db->deleteSql('business_district',"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function getListByOpidAction(){
        $id = I('get.op_id/d');
        $bus = new businessdistrictModel();
        $list = $bus->getList(['op_id'=>$id]);
        ajaxStatus($list);
    }
}
