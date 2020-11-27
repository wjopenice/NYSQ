<?php
use Yaf\Application;
class mercusercollectModel
{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getList($start,$showPage,$user_id){
        $result = $this->db->field("m_id,user_id")
            ->table("ny_merc_user_collect")
            ->where("user_id = {$user_id}")
            ->select();
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $tagList = array_column($tagList,'name','id');
        if(!empty($result)){
            $collect = array_column($result,"m_id","id");
            $intstr = implode(",",$collect);
            $result = $this->db
                ->field("R.id,R.m_id,M.merc_name,M.merc_logo,M.merc_info,M.merc_address,M.merc_sys_tag,M.merc_diy_tag,B.b_d_longitude,B.b_d_latitude")
                ->table("ny_merc_recom as R")
                ->join("ny_merc as M","R.m_id = M.m_id")
                ->join("ny_business_district as B","M.b_d_id = B.id")
                ->where("M.m_id IN({$intstr})")
                ->limit($start,$showPage)
                ->select();
            foreach($result as $k=>$v){
                $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
                $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
                $result[$k]['is_recom'] = 1 ;
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

    public function ischeck($user_id,$merc_id){
        $where = "m_id = {$merc_id} AND user_id = {$user_id}";
        $arrData = $this->db->field('m_id,user_id')
            ->table("ny_merc_user_collect")
            ->where($where)
            ->find();
        if(!empty($arrData['m_id'])){
            $bool = $this->db->action($this->db->deleteSql("merc_user_collect",$where));
        }else{
            $data['m_id'] = $merc_id;
            $data['user_id'] = $user_id;
            $bool = $this->db->action($this->db->insertSql("merc_user_collect",$data));
        }
        return $bool;
    }
}
