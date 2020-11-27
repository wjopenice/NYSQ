<?php
use Yaf\Application;
use Yaf\Dispatcher;
class ShopController extends Yaf\Controller_Abstract
{
    public $db;

    public function init()
    {
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

    public function shopaddAction(){
        $data = $_GET;
        $bool = $this->db->action($this->db->insertSql("product",$data));
        if($bool){
            $res = 'success';
        }else{
            $res = 'error';
        }
        $this->status($res);
    }

    public function shopeditAction(){
        $data = $_GET;
        $id = $data['id'];
        unset($data['id']);
        $data['p_banner'] = get_url($data,'p_banner');
        $data['p_pic1'] = get_url($data,'p_pic1');
        $data['p_pic2'] = get_url($data,'p_pic2');
        $data['p_pic3'] = get_url($data,'p_pic3');
        $data['p_pic4'] = get_url($data,'p_pic4');
        $data['p_pic5'] = get_url($data,'p_pic5');
        $bool = $this->db->action($this->db->updateSql("product",$data,"id = {$id}"));
        if($bool){
            $res = 'success';
        }else{
            $res = 'error';
        }
        $this->status($res);
    }

    public function shopfindAction(){
        $id = get('shop_id');
        $result = $this->db->field("*")
            ->table("ny_product")
            ->where("id = {$id}")
            ->find();
        if(!empty($result)){
            $result['p_banner'] = !empty($result['p_banner'])?[["url"=>APP_URI.$result['p_banner']]]:'';
            $result['p_pic1'] = !empty($result['p_pic1'])?[["url"=>APP_URI.$result['p_pic1']]]:'';
            $result['p_pic2'] = !empty($result['p_pic2'])?[["url"=>APP_URI.$result['p_pic2']]]:'';
            $result['p_pic3'] = !empty($result['p_pic3'])?[["url"=>APP_URI.$result['p_pic3']]]:'';
            $result['p_pic4'] = !empty($result['p_pic4'])?[["url"=>APP_URI.$result['p_pic4']]]:'';
            $result['p_pic5'] = !empty($result['p_pic5'])?[["url"=>APP_URI.$result['p_pic5']]]:'';
            $res['items'] = $result;
        }else{
            $res['items'] = [];
        }
        $this->status($res);
    }

    public function shoplistAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_product")
                ->where("p_name like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("product","*","total","p_name like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_product")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("product");
        }

        foreach ($arr as $k=>$v){
            $result = $this->db->field("id,m_id,merc_name")
                ->table("ny_merc")
                ->where("m_id = '{$v['m_id']}'")
                ->find();
            $arr[$k]['merc_name'] = $result['merc_name'];
            $arr[$k]['p_banner'] = APP_URI.$v['p_banner'];
            $arr[$k]['p_news'] = ($v['p_news'] == 1)?true:false;
            $arr[$k]['p_hot'] = ($v['p_hot'] == 1)?true:false;
            $arr[$k]['p_top'] = ($v['p_top'] == 1)?true:false;

        }

        $merc = $this->db->field("id,m_id,merc_name")
            ->table("ny_merc")
            ->select();
        $res['total'] = $count;
        $res['items'] = $arr;
        $res['merc'] = $merc;
        $this->status($res);
    }

    public function shopsortAction(){}

    public function inserttagAction(){
        $data['name'] = $_GET['name'];
        $data['type'] = $_GET['type'];
        $data['operator_id'] = $_GET['operator_id'];
        $data['created_time'] = date('Y-m-d H:i:s');
        $this->db->action($this->db->insertSql('tag', $data));
        $res = "success";
        $this->status($res);
    }

    public function removeAction(){
        $id = get("id");
        $bool = $this->db->action($this->db->deleteSql('product',"id = {$id}"));
        $res = $bool ? "success":"error";
        $this->status($res);
    }

    public function shopswitchAction(){
        $id = get("id");
        $status = (get("status") == 'true') ? 1 : 0;
        $type = get('type');
        $typename = '';
        switch($type){
            case "news": $typename = 'p_news';break;
            case "hot": $typename = 'p_hot';break;
            case "top": $typename = 'p_top';break;
        }
        $bool = $this->db->action($this->db->updateSql('product',[$typename=>$status],"id = {$id}"));
        $res = $bool ? "success":"error";
        $this->status($res);
    }

    public function tagListAction(){
        $sql = "select id,`name`,url from ny_tag where `type`=? and operator_id=?";
        $list = $this->db->preprocessing($sql,[$_GET['type'],$_GET['operator_id']]);
        echo json_encode($list);
    }
}
