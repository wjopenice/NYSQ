<?php
use Yaf\Application;
use Yaf\Dispatcher;
class SettingController extends Yaf\Controller_Abstract {
    public $db;
    public function init(){
        Dispatcher::getInstance()->autoRender(false);
        header('Access-Control-Allow-Origin:*');//允许所有来源访问
        header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-token');
        header('Access-Control-Allow-Method: POST, GET');//允许访问的方式
        if(strtoupper($_SERVER['REQUEST_METHOD'])== 'OPTIONS'){
            exit;
        }
        $this->db = new dbModel();
    }

    public function status($res){
        $result['code'] = 20000;
        $result['msg'] = 'success';
        $result['data'] = $res;
        echo json_encode($result,320);
        exit;
    }

    public function indexAction() {
        $res['items'] = $this->db->field("*")
            ->table("ny_setting")
            ->where("id = 1")
            ->find();
        $this->status($res);
    }

    public function filterAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;

        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_filter")
                ->where("word like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $total = (int)$this->db->zscount("filter","*","total","word like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_filter")
                ->limit($start,$limit)
                ->select();
            $total = (int)$this->db->zscount("filter");
        }
        $res['total'] = $total;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function thematicAction(){
        $arr = $this->db->field("*")
            ->table("ny_index_icon")
            ->select();
        $res['items'] = $arr;
        $this->status($res);
    }

    public function gettotalAction(){
        $arr['user'] = (int)$this->db->zscount("wechat_user");
        $arr['merc'] = (int)$this->db->zscount("merc");
        $arr['shop'] = (int)$this->db->zscount("product");
        $arr['operator'] = (int)$this->db->zscount("operator");
        $res['items'] = $arr;
        $this->status($res);
    }

    public function mercadminAction(){
        $arrData = $this->db->field("*")
            ->table("ny_merc_admin_icon")
            ->select();
        foreach($arrData as $k=>$v){
            $arrData[$k]['icon'] = APP_URI.$v['icon'];
        }
        $res['items'] = $arrData;
        $this->status($res);
    }

    public function addmercadminAction(){
        $data['title'] = get("title");
        $data['icon'] = get("icon");
        $data['sort'] = 0;
        $bool = $this->db->action($this->db->insertSql('merc_admin_icon',$data));
        if($bool){
            $res = 'success';
        }else{
            $res = 'error';
        }
        $this->status($res);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
