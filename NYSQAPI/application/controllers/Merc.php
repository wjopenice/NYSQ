<?php
use Yaf\Application;
use Yaf\Dispatcher;
class MercController extends Yaf\Controller_Abstract {
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

    public function tagAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $status = get('status');
        $start = ($page-1)*$limit;
        $arr = $this->db->field("*")
            ->table("ny_tag")
            ->where("type = {$status}")
            ->limit($start,$limit)
            ->select();
        $count = (int)$this->db->zscount("tag","*","total","type = {$status}");
        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function indexAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $sysTag = $this->db->field('name,id')
            ->table('ny_tag')
            ->where("type = 1")
            ->select();
        $diyTag = $this->db->field('name,id')
            ->table('ny_tag')
            ->where("type = 2")
            ->select();
        $bdList = $this->db->field('id,b_d_name,b_d_floor')
            ->table('ny_business_district')
            ->select();
        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_merc")
                ->where("merc_name like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("merc","*","total","merc_name like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_merc")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("merc");
        }
        foreach($arr as $key=>$value){
            $operator = $this->db->field("id,b_d_name")
                ->table("ny_business_district")
                ->where("id = {$value['b_d_id']}")
                ->find();
            $arr[$key]['b_d_name'] = $operator['b_d_name'];
            $arr[$key]['merc_logo'] = APP_URI.$value['merc_logo'];
            $arr[$key]['merc_sys_tag'] = explode(',',$value['merc_sys_tag']);
            $arr[$key]['merc_diy_tag'] = explode(',',$value['merc_diy_tag']);
        }
        $res['tagList'] = array_column($tagList,'name','id');
        $res['sysTag'] = $sysTag;
        $res['diyTag'] = $diyTag;
        $res['bdList'] = $bdList;
        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function statusAction() {
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $status = $_GET['status'];
        if(!empty($_GET['search'])) {
            $search = preg_replace("/[%_\s]+/", "", ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_merc")
                ->where("status = {$status} AND merc_name like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("merc","*","total","status = {$status} AND merc_name like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_merc")
                ->where("status = {$status}")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("merc","*","total","status = {$status}");
        }
        $tagList = $this->db->field('id,name')
            ->table('ny_tag')
            ->select();
        foreach($arr as $key=>$value){
            $operator = $this->db->field("id,b_d_name")
                ->table("ny_business_district")
                ->where("id = {$value['b_d_id']}")
                ->find();
            $arr[$key]['b_d_name'] = $operator['b_d_name'];
            $arr[$key]['merc_logo'] = APP_URI.$value['merc_logo'];
            $arr[$key]['merc_sys_tag'] = explode(',',$value['merc_sys_tag']);
            $arr[$key]['merc_diy_tag'] = explode(',',$value['merc_diy_tag']);
        }
        $res['tagList'] = array_column($tagList,'name','id');
        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function filterAction(){
        $id = get("id");
        $data = [];
        $switchtype = get('switchtype');
        switch($switchtype){
            case "success": $data = ["status"=>1]; break;  //通过
            case "error": $data = ["status"=>2]; break;  //驳回
            case "recover": $data = ["status"=>3]; break;  //回收
            case "apply": $data = ["status"=>0]; break;  //审核
        }
        $this->db->action($this->db->updateSql('merc',$data,"m_id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function removeAction(){
        $id = get("id");
        $this->db->action($this->db->deleteSql('tag',"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function removemercAction(){
        $id = get("id");
        $this->db->action($this->db->deleteSql('merc',"m_id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function selectfloorAction(){
        $id = get("id");
        $diyTag = $this->db->field('b_d_floor')
            ->table('ny_business_district')
            ->where("id = {$id}")
            ->find();
        if(!empty($diyTag)){
            $res['items'] = explode(',',$diyTag['b_d_floor']);
        }else{
            $res['items'] = [];
        }
        $this->status($res);
    }

    public function savetagAction(){
        $id = get("id");
        $name = get("name");
        $this->db->action($this->db->updateSql('tag',['name'=>$name],"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function insertmercAction(){
        $input = file_get_contents("php://input");
        $data = json_decode($input,true);
        $data['m_id'] = rand("100000","999999");
        $data['merc_sys_tag'] = implode(",",$data['merc_sys_tag']);
        $data['merc_diy_tag'] = implode(",",$data['merc_diy_tag']);
        $data['merc_banner'] = implode(",",$data['merc_banner']);
        $data['merc_wechat_qrcode'] = implode(",",$data['merc_wechat_qrcode']);
        $data['create_time'] = time();
        $data['status'] = 0;
        $bool = $this->db->action($this->db->insertSql('merc',$data));
        $res = $bool ? "success" : "error";
        $this->status($res);
    }

    public function updatemercAction(){
        $input = file_get_contents("php://input");
        $data = json_decode($input,true);
        $id = $data['id'];
        unset($data['id']);
        unset($data['b_d_name']);
        $data['merc_sys_tag'] = implode(",",$data['merc_sys_tag']);
        $data['merc_diy_tag'] = implode(",",$data['merc_diy_tag']);
        $data['merc_logo'] = get_url($data,'merc_logo');
        $data['merc_banner'] = get_url($data,'merc_banner');
        $data['merc_wechat_qrcode'] = get_url($data,'merc_wechat_qrcode');
        $data['merc_wechat_pub_qrcode'] = get_url($data,'merc_wechat_pub_qrcode');
        $data['merc_weshop_photo_qrcode'] = get_url($data,'merc_weshop_photo_qrcode');
        $data['merc_video_qrcode'] = get_url($data,'merc_video_qrcode');
        $data['merc_live_qrcode'] = get_url($data,'merc_live_qrcode');
        $data['merc_zan_qrcode'] = get_url($data,'merc_zan_qrcode');
        $bool = $this->db->action($this->db->updateSql('merc',$data,"m_id = {$id}"));
        $res = $bool ? "success" : "error";
        $this->status($res);
    }

    public function findlistAction(){
        $id = get("editid");
        $data = $this->db->field('*')
            ->table('ny_merc')
            ->where("m_id = {$id}")
            ->find();
        $xxx = $this->db->field("id,b_d_name,b_d_floor")
            ->table("ny_business_district")
            ->where("id = '{$data['b_d_id']}'")
            ->find();
        $data['b_d_name'] = $xxx['b_d_name'];
        $data['merc_logo'] = !empty($data['merc_logo'])?[["url"=>APP_URI.$data['merc_logo']]]:'';
        if(!empty($data['merc_banner'])){
            $maplist1 = explode(",",$data['merc_banner']);
            for($i=0;$i<count($maplist1);$i++){
                $res[$i] = ["url"=>APP_URI.$maplist1[$i]];
            }
        }else{
            $res = '';
        }
        $data['merc_banner'] = $res;
        if(!empty($data['merc_wechat_qrcode'])){
            $maplist2 = explode(",",$data['merc_wechat_qrcode']);
            for($j=0;$j<count($maplist2);$j++){
                $res2[$j] = ["url"=>APP_URI.$maplist2[$j]];
            }
        }else{
            $res2 = '';
        }
        $data['merc_wechat_qrcode'] = $res2;
        $data['merc_wechat_pub_qrcode'] = !empty($data['merc_wechat_pub_qrcode'])?[["url"=>APP_URI.$data['merc_wechat_pub_qrcode']]]:'';
        $data['merc_weshop_photo_qrcode'] = !empty($data['merc_weshop_photo_qrcode'])?[["url"=>APP_URI.$data['merc_weshop_photo_qrcode']]]:'';
        $data['merc_video_qrcode'] = !empty($data['merc_video_qrcode'])?[["url"=>APP_URI.$data['merc_video_qrcode']]]:'';
        $data['merc_live_qrcode'] = !empty($data['merc_live_qrcode'])?[["url"=>APP_URI.$data['merc_live_qrcode']]]:'';
        $data['merc_zan_qrcode'] = !empty($data['merc_zan_qrcode'])?[["url"=>APP_URI.$data['merc_zan_qrcode']]]:'';

        $sysTag = $this->db->field('name,id')
            ->table('ny_tag')
            ->where("type = 1")
            ->select();
        $diyTag = $this->db->field('name,id')
            ->table('ny_tag')
            ->where("type = 2")
            ->select();
        $bdList = $this->db->field('id,b_d_name,b_d_floor')
            ->table('ny_business_district')
            ->select();

        $resp['sysTag'] = $sysTag;
        $resp['diyTag'] = $diyTag;
        $resp['bdList'] = $bdList;
        $resp['findData'] = $data;
        $resp['floor'] = $xxx['b_d_floor'];
        $this->status($resp);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
