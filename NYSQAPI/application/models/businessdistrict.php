<?php
use Yaf\Application;
class businessdistrictModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getList($data=[]){
        $this->db->field("id,b_d_name,b_d_floor")
            ->table("ny_business_district");
        if(!empty($data['op_id'])) $this->db->where('op_id='.$data['op_id']);
        if(!empty($data['ids'])) $this->db->where(" id in({$data['ids']})");
        $result = $this->db->select();
        return $result;
    }

    public function getfloor($id){
        $result = $this->db
            ->field("b_d_floor")
            ->table("ny_business_district")
            ->where("id = {$id}")
            ->find();
        if(!empty($result['b_d_floor'])){
            $result = explode(",",$result['b_d_floor']);
        }else{
            $result = NULL;
        }
        return $result;
    }

    public function getbd(){
        $result = $this->db
            ->field("id as b_d_id,op_id,b_d_name,b_d_info,b_d_logo,b_d_longitude,b_d_latitude,b_d_address")
            ->table("ny_business_district")
            ->select();
        if(!empty($result)){
            foreach($result as $k=>$v){
                $count = (int)$this->db->zscount("merc","*","total","b_d_id = {$v['b_d_id']}");
                $result[$k]['merc_num'] = $count;
            }
            return $result;
        }else{
            return NULL;
        }
    }

    public function getbdfind($bdid){
        $result = $this->db
            ->field("id as b_d_id,op_id,b_d_name,b_d_info,b_d_logo,b_d_longitude,b_d_latitude,b_d_address")
            ->table("ny_business_district")
            ->where("id = {$bdid}")
            ->find();
        if(!empty($result)){
            $count = (int)$this->db->zscount("merc","*","total","b_d_id = {$result['b_d_id']}");
            $result['merc_num'] = $count;
            return $result;
        }else{
            return NULL;
        }
    }

    public function getmerc($user,$bdid,$start,$showPage){
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $tagList = array_column($tagList,'name','id');
        $result = $this->db
            ->field("M.id,M.m_id,M.merc_name,M.merc_logo,M.merc_info,M.merc_address,M.merc_sys_tag,M.merc_diy_tag,B.b_d_longitude,B.b_d_latitude")
            ->table("ny_merc as M")
            ->join("ny_business_district as B","M.b_d_id = B.id")
            ->where("M.b_d_id = {$bdid}")
            ->limit($start,$showPage)
            ->select();
        if(!empty($result)){
            foreach($result as $k=>$v){
                $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
                $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
                $iscollect = $this->db->field("m_id,user_id")
                    ->table("ny_merc_user_collect")
                    ->where("user_id = {$user} AND m_id = '{$v['m_id']}'")
                    ->find();
                $result[$k]['is_recom'] = !empty($iscollect['m_id'])?1:0;
            }
            return $result;
        }else{
            return NULL;
        }
    }

    public function tag($data,$tagList){
        if(!empty($data)){
            $arr = explode(",",$data);
            $arr2 = [];
            foreach ($arr as $k1=>$v2){
                $arr2[$k1] = $tagList[$v2];
            }
            return $arr2;
        }else{
            return NULL;
        }
    }
}
