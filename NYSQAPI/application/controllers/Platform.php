<?php
use Yaf\Application;
use Yaf\Dispatcher;
class PlatformController extends Yaf\Controller_Abstract
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

    public function getlistAction(){
        $limit = empty($_GET['limit'])?8:$_GET['limit'];
        $page = empty($_GET['page'])?1:$_GET['page'];
        $start = ($page-1)*$limit;
        if(!empty($_GET['search'])){
            $search = preg_replace("/[%_\s]+/","",ltrim(addslashes($_GET['search'])));
            $arr = $this->db->field("*")
                ->table("ny_wechat_user")
                ->where("nickname like '%{$search}%'")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("wechat_user","*","total","nickname like '%{$search}%'");
        }else{
            $arr = $this->db->field("*")
                ->table("ny_wechat_user")
                ->limit($start,$limit)
                ->select();
            $count = (int)$this->db->zscount("wechat_user");
        }
        $res['total'] = $count;
        $res['items'] = $arr;
        $this->status($res);
    }

    public function rolesAction(){
        $arr = $this->db->field("*")
            ->table("ny_user_roles")
            ->select();
        $res['items'] = $arr;
        $this->status($res);
    }

    public function addrolesAction(){
        $data['roles'] = get('roles');
        $this->db->action($this->db->insertSql("user_roles",$data));
        $res = "success";
        $this->status($res);
    }


}
