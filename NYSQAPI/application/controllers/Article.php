<?php
use Yaf\Application;
use Yaf\Dispatcher;
class ArticleController extends Yaf\Controller_Abstract {
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
        $type = $this->db->field("id,style")
            ->table("ny_index_style")
            ->select();
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $total = $this->db->field("na.id,ni.style,na.title,na.pic,na.content,na.create_time,na.status")
            ->table("ny_article as na")
            ->join("ny_index_style as ni","ni.id = na.type")
            ->select();
        $res['total'] = count($total);
        $arr = $this->db->field("na.id,ni.style,na.title,na.pic,na.content,na.create_time")
            ->table("ny_article as na")
            ->join("ny_index_style as ni","ni.id = na.type")
            ->limit($start,$limit)
            ->select();
        foreach($arr as $k=>$v){
            $arr[$k]['pic'] = APP_URI."/public/images/article/".$v['pic'] ;
        }
        $res['items'] = $arr;
        $res['type'] = $type;
        $this->status($res);
    }

    public function statusAction() {
        $type = $this->db->field("id,style")
            ->table("ny_index_style")
            ->select();
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $status = $_GET['status'];
        $total = $this->db->field("na.id,ni.style,na.title,na.pic,na.content,na.create_time,na.status")
            ->table("ny_article as na")
            ->join("ny_index_style as ni","ni.id = na.type")
            ->where("na.status = {$status}")
            ->select();
        $res['total'] = count($total);
        $arr = $this->db->field("na.id,ni.style,na.title,na.pic,na.content,na.create_time,na.status")
            ->table("ny_article as na")
            ->join("ny_index_style as ni","ni.id = na.type")
            ->where("na.status = {$status}")
            ->limit($start,$limit)
            ->select();
        foreach($arr as $k=>$v){
            $arr[$k]['pic'] = APP_URI."/public/images/article/".$v['pic'] ;
        }
        $res['items'] = $arr;
        $res['type'] = $type;
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
        $this->db->action($this->db->updateSql('article',$data,"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function typelistAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $arr = $this->db->field("*")
            ->table("ny_index_style")
            ->limit($start,$limit)
            ->select();
        foreach($arr as $k=>$v){
            $arr[$k]['pic'] = APP_URI."/public/images/article/".$v['pic'] ;
        }
        $res['items'] = $arr ;
        $this->status($res);
    }

    public function removeAction(){
        $id = get("id");
        $this->db->action($this->db->deleteSql('article',"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function specialsubjectAction(){
        $arr = $this->db->field("id,title")
            ->table("ny_thematic")
            ->select();
        $res['items'] = $arr;
        $this->status($res);
    }

    public function insertspecialAction(){
        $data['title'] = get("title");
        $this->db->action($this->db->insertSql('thematic',$data));
        $res = "success";
        $this->status($res);
    }

    public function deletespecialAction(){
        $id = get("id");
        $this->db->action($this->db->deleteSql('thematic'," id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function emptyAction()
    {
        // TODO: Implement __call() method.
    }

}
?>
