<?php
use Yaf\Application;
class mercviceModel{

    public $db;

    public function __construct()
    {
        $this->db = new dbModel();
    }

    public function icon(){
        $arrData = $this->db->field("id,title,icon")
            ->table("ny_merc_admin_icon")
            ->select();
        if(!empty($arrData)){
            foreach($arrData as $k=>$v){
                $arrData[$k]['icon'] = $v['icon'];
            }
            return $arrData;
        }else{
            return [];
        }
    }

    public function getuserinfo($user_id,$m_id = null){
        $result = $this->db
            ->field("id as user_id,nickname,headimgurl")
            ->table("ny_wechat_user")
            ->where("id = {$user_id}")
            ->find();
        if(!empty($result['nickname'])){
            if(is_null($m_id)){
                $result['competence'] = "普通用户";
                $result['visitors_today'] = 0;
                $result['visitors_total'] = 0;
                $result['keep'] = 0;
                $result['collect_num'] = 0;
            }else{
                $result['competence'] = ""; //后期确定 （运营商|商家|普通用户）
                $result['visitors_today'] = 0; //后期确定
                $result['visitors_total'] = 0; //后期确定
                $result['keep'] = 0; //后期确定
                $result['collect_num'] = 0; //后期确定 数据来自ny_merc_user_collect表
            }
            return $result;
        }else{
            return [];
        }
    }

    public function switchtag($status = 1){
        $arrData = $this->db->field("id,name")
            ->table("ny_tag")
            ->where("type = {$status}")
            ->select();
        return $arrData;
    }

    public function addswitchtag($name,$status = 1){
        $result = $this->db->field("name")
            ->table("ny_tag")
            ->where("type = 1 and name='{$name}'")
            ->find();
        if(!empty($result)){
            return 1008;
        }else{
            $data['name'] = $name;
            $data['type'] = ($status == 1) ? 1 : 2;
            $data['operator_id'] = 1;
            $data['created_time'] = date("Y-m-d H:i:s",time());
            $bool = $this->db->action($this->db->insertSql('tag',$data));
            $code = $bool ? 200 : 500;
            return $code;
        }
    }

    public function addmerc($input){
        $data = $input;
        if(!empty($data['merc_banner'])){
            $data['merc_banner'] = implode(",",$data['merc_banner']);
        }

        $data['m_id'] = rand("100000","999999");
        $data['merc_sys_tag'] = implode(",",$data['merc_sys_tag']);
        $data['merc_diy_tag'] = implode(",",$data['merc_diy_tag']);
        $data['merc_tel'] = implode(",",$data['merc_tel']);
        $data['merc_wechat_qrcode'] = implode(",",$data['merc_wechat_qrcode']);
        $data['create_time'] = time();
        $data['status'] = 0;
        $bool = $this->db->action($this->db->insertSql('merc',$data));
        $code = $bool ? 200 : 500;
        return $code;
    }
}
