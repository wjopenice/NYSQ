<?php

use Yaf\Application;
use Yaf\Dispatcher;
class UserController extends Yaf\Controller_Abstract
{
    public $db;
    public $data;
    public $roleArr;

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
        $this->data = file_get_contents("php://input");
        $this->roleArr = [1=>'超级管理员',2=>'普通管理员',3=>'商户',4=>'个人'];  //暂定，后面会放角色表的
    }

    public function listAction(){
        $limit = I('get.limit/d',10);
        $page = I('get.page/d',1);
        $username = I('get.username/s');
        $competence = I('get.competence/d');
        $start = ($page-1)*$limit;
        if (!empty($username) && !empty($competence)){
            $list = $this->db->field("id,username,competence,created_time")
                ->table("ny_system")
                ->where("competence = {$competence} and username ")
                ->like($username)
                ->limit($start,$limit)
                ->select();
            $total = $this->db->field("*")
                ->table("ny_system")
                ->where("competence = {$competence} and username ")
                ->like($username)
                ->select();
        }elseif(!empty($username)){
            $list = $this->db->field("id,username,competence,created_time")
                ->table("ny_system")
                ->where(" username ")
                ->like($username)
                ->limit($start,$limit)
                ->select();
            $total = $this->db->field("*")
                ->table("ny_system")
                ->where(" username ")
                ->like($username)
                ->select();
        }elseif (!empty($competence)){
            $list = $this->db->field("id,username,competence,created_time")
                ->table("ny_system")
                ->where("competence =".$competence)
                ->limit($start,$limit)
                ->select();
            $total = $this->db->field("*")
                ->table("ny_system")
                ->where("competence =".$competence)
                ->select();
        }else {
            $list = $this->db->field("id,username,competence,created_time")
                ->table("ny_system")
                ->limit($start, $limit)
                ->select();
            $total = $this->db->field("*")
                ->table("ny_system")
                ->select();
        }
        ajaxStatus(['list'=>$list,'total'=>count($total),'roleArr'=>$this->roleArr]);
    }

    public function editAction(){
        $data = json_decode($this->data,true);
        if(empty($data['id'])) {
            $pass = hmac256($data['password']);
            $map = [
                'username' => $data['username'],
                'password' => $pass,
                'competence' => $data['competence'],
                'created_time' => date('Y-m-d H:i:s'),
                'ip' => server("REMOTE_ADDR")
            ];
            $res = $this->db->action($this->db->insertSql('system', $map));
            if (!empty($res)) ajaxStatus('新增成功');
            ajaxError('新增失败');
        }else{
            $map = [
                'username' => $data['username'],
                'competence' => $data['competence'],
                'updated_time' => date('Y-m-d H:i:s'),
                'ip' => server("REMOTE_ADDR"),
            ];
            $res = $this->db->action($this->db->updateSql('system', $map,'id ='.$data['id']));
            if (!empty($res)) ajaxStatus('修改成功');
            ajaxError('修改失败');
        }
    }

    public function delAction(){
        $id = I('get.id/d');
        $res = $this->db->action($this->db->deleteSql('system','id='.$id));
        if(!empty($res)) ajaxStatus('删除成功');
        ajaxError('删除失败');
    }
}
