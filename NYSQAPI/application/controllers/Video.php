<?php
use Yaf\Application;
use Yaf\Dispatcher;
class VideoController extends BaseController
{
    public $db;

    public function init()
    {
        \Yaf\Dispatcher::getInstance()->autoRender(false);
        $this->db = new dbModel();
    }

    public function addAction(){
        if($this->getRequest()->isPost()){
            $name = I('post.name/s');
            $type = I('post.type/d');
            $operator_id = I('post.operator_id/d');
            $img_url = I('post.img_url/s');
            $url = I('post.url/s');
            $map = [
                'name'=>$name,
                'type'=>$type,
                'img_url'=>$img_url,
                'url'=>$url,
                'created_time'=>date('Y-m-d H:i:s'),
                'updated_time'=>date('Y-m-d H:i:s')
            ];
            if(!empty($operator_id)) $map['operator_id'] = $operator_id;
            $res = $this->db->action($this->db->insertSql('ny_tag',$map));
            if(!empty($res)){
                $this->status('添加成功');
            }else{
                $this->error('添加失败');
            }
        }
        $this->getView()->display('img/img.phtml');
    }

    public function tagListAction(){
        $sql = "select id,`name`,url from ny_tag where `type`=? and operator_id=?";
        $list = $this->db->preprocessing($sql,[$_GET['type'],$_GET['operator_id']]);
        echo json_encode($list);
    }
}
