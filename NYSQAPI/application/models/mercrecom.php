<?php
use Yaf\Application;
class mercrecomModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function recomList($start,$showPage,$user_id){
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $tagList = array_column($tagList,'name','id');
        $result = $this->db
            ->field("R.id,R.m_id,M.merc_name,M.merc_logo,M.merc_info,M.merc_address,M.merc_sys_tag,M.merc_diy_tag,B.b_d_longitude,B.b_d_latitude")
            ->table("ny_merc_recom as R")
            ->join("ny_merc as M","R.m_id = M.m_id")
            ->join("ny_business_district as B","M.b_d_id = B.id")
            ->limit($start,$showPage)
            ->select();
        foreach($result as $k=>$v){
            $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
            $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
            $result[$k]['is_recom'] = 0 ; //是否收藏商家
        }
        return $result;
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
