<?php
use Yaf\Application;
class mercModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getinfo($user_id,$merc_id){
        $tagList = $this->db->field('name,id')
            ->table('ny_tag')
            ->select();
        $tagList = array_column($tagList,'name','id');

        $mercData = $this->db->field("*")
            ->table("ny_merc")
            ->where("m_id = '{$merc_id}'")
            ->find();
        if(!empty($mercData)){
            $Lbs = $this->db->field('b_d_longitude,b_d_latitude')
                ->table('ny_business_district')
                ->where("id = {$mercData['b_d_id']}")
                ->find();
            unset($mercData['id']);
            unset($mercData['b_d_id']);
            unset($mercData['status']);
            unset($mercData['create_time']);
            $mercData['b_d_longitude'] = $Lbs['b_d_longitude'];
            $mercData['b_d_latitude'] = $Lbs['b_d_latitude'];
            $mercData['merc_banner'] = explode(",",$mercData['merc_banner']);
            $mercData['merc_tel'] = explode(",",$mercData['merc_tel']);
            $mercData['merc_wechat_qrcode'] = explode(",",$mercData['merc_wechat_qrcode']);
            $mercData['merc_sys_tag'] = $this->tag($mercData['merc_sys_tag'],$tagList);
            $mercData['merc_diy_tag'] = $this->tag($mercData['merc_diy_tag'],$tagList);
            $iscollect = $this->db->field("m_id,user_id")
                ->table("ny_merc_user_collect")
                ->where("user_id = {$user_id} AND m_id = '{$merc_id}'")
                ->find();
            $mercData['is_recom'] = !empty($iscollect['m_id'])?1:0;
            $mercData['look_num'] = 0;
            return $mercData;
        }else{
            return [];
        }
    }

    public function getAllList($start,$showPage,$map,$user = NULL){

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
        if(!is_null($user)){
            foreach($result as $k=>$v){
                $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
                $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
                $iscollect = $this->db->field("m_id,user_id")
                    ->table("ny_merc_user_collect")
                    ->where("user_id = {$user} AND m_id = '{$v['m_id']}'")
                    ->find();
                $result[$k]['is_recom'] = !empty($iscollect['m_id'])?1:0;
            }
        }else{
            foreach($result as $k=>$v){
                $result[$k]['merc_sys_tag'] = $this->tag($v['merc_sys_tag'],$tagList);
                $result[$k]['merc_diy_tag'] = $this->tag($v['merc_diy_tag'],$tagList);
                $result[$k]['is_recom'] = 0 ;
            }
        }

        return $result;
    }

    public function getShopList($user_id,$merc_id,$type,$start,$showPage){
        $map = "";
        switch ($type){
            case "all":
                $map = "m_id = '{$merc_id}'";
            break;
            case "news":
                $map = "m_id = '{$merc_id}' AND p_news = 1";
            break;
            case "hot":
                $map = "m_id = '{$merc_id}' AND p_hot = 1";
            break;
        }
        $arrData = $this->db->field("id as shop_id,p_name,p_banner")
            ->table("ny_product")
            ->where($map)
            ->limit($start,$showPage)
            ->select();
        if(!empty($arrData)){
            return $arrData;
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
