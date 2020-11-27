<?php
use Yaf\Application;
class searchModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getUserLog($user_id){
        $result = $this->db->field("search")
            ->table("ny_search")
            ->where("user_id = {$user_id}")
            ->limit(0,10)
            ->select();
        if(!empty($result)){
            return $result;
        }else{
            return [];
        }
    }

    public function getLog(){
        $result = $this->db->field("search")
            ->table("ny_search")
            ->group("search")
            ->order("count(*) DESC")
            ->limit(0,10)
            ->select();
        if(!empty($result)){
            return $result;
        }else{
            return [];
        }
    }

    public function getList($user_id,$search,$start,$showPage){
        $inser['search'] = $search;
        $inser['user_id'] = $user_id;
        $this->db->action($this->db->insertSql("search",$inser));
        $search = preg_replace("/[%_]+/","",ltrim(addslashes(trim($search))));
        $shopData = $this->db->field("m_id,p_name,p_description")->table("ny_product")->where("(p_name like '%{$search}%') OR (p_description like '%{$search}%')")->select();
        if(!empty($shopData)){
            $newArr = array_column($shopData,'m_id','id');
            $str = implode(",",$newArr);
            $map = "M.m_id IN ({$str})";
        }else{
            $map = "(M.merc_name like '%{$search}%') OR (M.merc_info like '%{$search}%') OR (M.merc_description like '%{$search}%')";
        }
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $tagList = array_column($tagList,'name','id');
        $result = $this->db
            ->field("M.id,M.m_id,M.merc_name,M.merc_logo,M.merc_info,M.merc_address,M.merc_sys_tag,M.merc_diy_tag,B.b_d_longitude,B.b_d_latitude")
            ->table("ny_merc as M")
            ->join("ny_business_district as B","M.b_d_id = B.id")
            ->where($map)
            ->limit($start,$showPage)
            ->select();
        if(!empty($result)){
            foreach($result as $k=>$v){
                $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
                $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
                $iscollect = $this->db->field("m_id,user_id")
                    ->table("ny_merc_user_collect")
                    ->where("user_id = {$user_id} AND m_id = '{$v['m_id']}'")
                    ->find();
                $result[$k]['is_recom'] = !empty($iscollect['m_id'])?1:0;
            }
            return $result;
        }else{
            return [];
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
