<?php
use Yaf\Application;
class shopModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function getinfo($user_id,$shop_id){
        $shopData = $this->db->field("P.*,M.merc_tel,M.merc_wechat_qrcode")
            ->table("ny_product as P")
            ->join("ny_merc as M","M.m_id = P.m_id")
            ->where("P.id = {$shop_id}")
            ->find();
        if(!empty($shopData)){
            $shopData['shop_id'] = $shopData['id'];
            $shopData['merc_tel'] = explode(",",$shopData['merc_tel']);
            $shopData['merc_wechat_qrcode'] = explode(",",$shopData['merc_wechat_qrcode']);
            unset($shopData['id']);
            unset($shopData['p_top']);
            unset($shopData['p_sort']);
            return $shopData;
        }else{
            return (object)[];
        }
    }

    public function addshop($input){
        $data = $input;
        $data['p_news'] = empty($data['p_news']) ? 0 : $data['p_news'];
        $data['p_hot'] = empty($data['p_hot']) ? 0 : $data['p_hot'];
        $arrData = explode(",",$input['p_pic']);
        $count = count($arrData);
        if($count >= 2){
            $data['p_banner'] = $arrData[0];
            for($i=1;$i<$count;$i++){
                $arrData["p_pic".$i] = $arrData[$i];
            }
        }else{
            $data['p_banner'] = $arrData[0];
        }
        unset($input['p_pic']);
        $data['p_top'] = 0;
        $data['p_sort'] = 0;
        $bool =  $this->db->action($this->db->insertSql("product",$data));
        $code = $bool ? 200 : 500;
        return $code;
    }

    public function editshop($input){
        $data = $input;
        $map = "m_id = '{$data['m_id']}' AND id = {$data['shop_id']}";
        unset($data['m_id']);
        unset($data['shop_id']);
        $data['p_news'] = empty($data['p_news']) ? 0 : $data['p_news'];
        $data['p_hot'] = empty($data['p_hot']) ? 0 : $data['p_hot'];
        $arrData = explode(",",$input['p_pic']);
        $count = count($arrData);
        if($count >= 2){
            $data['p_banner'] = $arrData[0];
            for($i=1;$i<$count;$i++){
                $arrData["p_pic".$i] = $arrData[$i];
            }
        }else{
            $data['p_banner'] = $arrData[0];
        }
        unset($input['p_pic']);
        $bool =  $this->db->action($this->db->updateSql("product",$data,$map));
        $code = $bool ? 200 : 500;
        return $code;
    }

    public function statusshoplist($m_id,$status){
        $map = '';
        switch($status){
            case "all":
                $map = "m_id = '{$m_id}' AND p_status = 1";
                break;
            case "news":
                $map = "m_id = '{$m_id}' AND p_news = 1 AND p_status = 1";
                break;
            case "hot":
                $map = "m_id = '{$m_id}' AND p_hot = 1 AND p_status = 1";
                break;
            case "down":
                $map = "m_id = '{$m_id}' AND p_status = 0";
                break;
        }
        $data = $this->db->field("id as shop_id,m_id,p_name,p_description,p_banner,p_news,p_hot,p_top,p_status")
            ->table("ny_product")
            ->where($map)
            ->select();
        if(!empty($data)){
            return $data;
        }else{
            return [];
        }
    }

    public function newest($m_id,$shop_id){
        $data = [];
        $map = "m_id = '{$m_id}' AND id = {$shop_id}";
        $result = $this->db->field("*")
            ->table("ny_product")
            ->where($map)
            ->find();
        if($result['p_news'] == 1){
            $data["p_news"] = 0;
        }else{
            $data["p_news"] = 1;
        }
        $bool = $this->db->action($this->db->updateSql("product",$data,$map));
        $code = $bool ? 200 : 500;
        return $code;
    }

    public function explosion($m_id,$shop_id){
        $data = [];
        $map = "m_id = '{$m_id}' AND id = {$shop_id}";
        $result = $this->db->field("*")
            ->table("ny_product")
            ->where($map)
            ->find();
        if($result['p_hot'] == 1){
            $data["p_hot"] = 0;
        }else{
            $data["p_hot"] = 1;
        }
        $bool = $this->db->action($this->db->updateSql("product",$data,$map));
        $code = $bool ? 200 : 500;
        return $code;
    }

    public function shelves($m_id,$shop_id){
        $data = [];
        $map = "m_id = '{$m_id}' AND id = {$shop_id}";
        $result = $this->db->field("*")
            ->table("ny_product")
            ->where($map)
            ->find();
        if($result['p_status'] == 1){
            $data["p_status"] = 0;
        }else{
            $data["p_status"] = 1;
        }
        $bool = $this->db->action($this->db->updateSql("product",$data,$map));
        $code = $bool ? 200 : 500;
        return $code;
    }

    public function remove($m_id,$shop_id){
        $map = "m_id = '{$m_id}' AND id = {$shop_id}";
        $bool = $this->db->action($this->db->dateleSql("product",$map));
        $code = $bool ? 200 : 500;
        return $code;
    }


}
