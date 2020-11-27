<?php
use Yaf\Application;
class relesmanModel
{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function thematiclist($zone_id,$user_id){
        $result = $this->db->field("id,zone_id,title,content,pic,")
            ->table("ny_thematic_article")
            ->where("zone_id = {$zone_id} and user_id = {$user_id}")
            ->select();
        if(!empty($result[0]['id'])){
            return $result;
        }else{
            return [];
        }
    }

    public function thematic(){
        $result = $this->db->field("id as zone_id,title")
            ->table("ny_thematic")
            ->select();
        if(!empty($result[0]['id'])){
            return $result;
        }else{
            return [];
        }
    }

    public function addthematic($inpt){
        $data['zone_id'] = $inpt['zone_id'];
        $data['title'] = $inpt['title'];
        $data['content'] = $inpt['content'];
        $data['user_id'] = $inpt['user_id'];
        $data['pic'] = $inpt['pic'];
        $data['create_time'] = time();
        $bool = $this->db->action($this->db->insertSql("thematic_article",$data));
        $res = $bool ? 200 : 500;
        return $res;
    }
}
