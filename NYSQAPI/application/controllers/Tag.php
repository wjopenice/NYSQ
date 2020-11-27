<?php
use Yaf\Application;
use Yaf\Dispatcher;
class TagController extends BaseController
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

    public function addAction(){
        if($this->getRequest()->isPost()){
            $name = I('post.name/s');
            $type = I('post.type/d');
            $operator_id = I('post.operator_id/d');
            $map = [
                'name'=>$name,
                'type'=>$type,
                'created_time'=>date('Y-m-d H:i:s')
            ];
            if(!empty($operator_id)) {
                $map['operator_id'] = $operator_id;
                $bool = $this->db->action($this->db->insertSql('tag',$map));
                if(!empty($bool)){
                    $res = '添加成功';
                }else{
                    $res = '添加失败';
                }
                $this->status($res);
            };
        }
    }

    public function listAction(){
        $limit = empty($_GET['limit'])?10:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        $status = get('status');
        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_tag")
                ->where("name like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("tag","*","total","name like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_tag")
                ->where("type = {$status}")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("tag","*","total","type = {$status}");
        }
        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

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
        $this->db->action($this->db->deleteSql('tag',"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function savetagAction(){
        $id = get("id");
        $name = get("name");
        $this->db->action($this->db->updateSql('tag',['name'=>$name],"id = {$id}"));
        $res = "success";
        $this->status($res);
    }

    public function tagListAction(){
        $sql = "select id,`name`,url from ny_tag where `type`=? and operator_id=?";
        $list = $this->db->preprocessing($sql,[$_GET['type'],$_GET['operator_id']]);
        echo json_encode($list);
    }
}
